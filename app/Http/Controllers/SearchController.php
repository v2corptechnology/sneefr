<?php namespace Sneefr\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Session;
use Sneefr\Jobs\SaveSearch;
use Sneefr\Models\PlaceName;
use Sneefr\Repositories\Category\CategoryRepository;
use Sneefr\Repositories\Place\PlaceRepository;
use Sneefr\Repositories\Search\SearchRepository;
use Sneefr\Services\SearchService;

class SearchController extends Controller {
    /**
     * @var \Sneefr\Repositories\Category\CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var \Sneefr\Repositories\Place\PlaceRepository
     */
    protected $placeRepository;

    /**
     * @param \Sneefr\Repositories\Category\CategoryRepository $categoryRepository
     * @param \Sneefr\Repositories\Place\PlaceRepository       $placeRepository
     */
    public function __construct(CategoryRepository $categoryRepository, PlaceRepository $placeRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->placeRepository = $placeRepository;
    }

    /**
     * @param \Illuminate\Http\Request       $request
     * @param \Sneefr\Services\SearchService $search
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, SearchService $search)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'ad');

        $ads = $search->for('ad')->with($request->all());
        $shops = $search->for('Shop')->with($request->all());

        // When displaying ads, detect commonly linked categories to query terms
        if ($type === 'ad') {
            $linkedCategories = $this->getLinkedCategories($query);
        }

        // Log the search action
        Queue::push(new SaveSearch($query, auth()->id(), $request));

        return view('search.index', compact('ads', 'shops', 'linkedCategories', 'query', 'type', 'request'));
    }

    /**
     * Remove the specified resource from storage.
     * TODO: Use formRequest
     *
     * @param                                              $searchId
     * @param \Sneefr\Repositories\Search\SearchRepository $searchRepository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($searchId, SearchRepository $searchRepository)
    {
        $searchRepository->delete($searchId);

        return back()->with('success', trans('feedback.search_deleted_success'));
    }

    /**
     * Publish a search on the person's profile
     * TODO: Use formRequest
     *
     * @param Request                                      $request
     * @param \Sneefr\Repositories\Search\SearchRepository $searchRepository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, SearchRepository $searchRepository)
    {
        $searchRepository->create(auth()->id(), $request->get('query'));

        return redirect()->route('home')
            ->with('success', trans('feedback.search_shared', ['search' => $request->get('query')]));
    }

    /**
     * Check if one of the query string terms correspond to one of our categories
     *
     * @param string  $query
     *
     * @return array
     */
    protected function getLinkedCategories($query)
    {
        // Transform diacritics to "normal" equivalent
        $regexp = '/&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);/i';
        $query =  html_entity_decode(preg_replace($regexp, '$1', htmlentities($query)));

        // Split individual terms
        $terms = explode(' ', $query);

        // Load the associations in the user's locale
        $categoryAssociations = json_decode(Storage::disk('local')->get(config('app.locale').'_search_association.json'));

        $associatedCategories = [];
        foreach ($terms as $term) {
            foreach ($categoryAssociations as $categoryId => $associations) {
                if (in_array($term, array_map('strtolower', $associations))) {
                    $associatedCategories[] = $categoryId;
                }
            }
        }

        return $associatedCategories;
    }
}
