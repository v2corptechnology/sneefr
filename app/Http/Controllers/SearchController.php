<?php

namespace Sneefr\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Session;
use Sneefr\Jobs\SaveSearch;
use Sneefr\Models\Ad;
use Sneefr\Models\Shop;

class SearchController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'ad');

        $ads = Ad::search($query)->get()->take(20);
        $shops = Shop::search($query)->get()->take(20);

        // When displaying ads, detect commonly linked categories to query terms
        if ($type === 'ad') {
            $linkedCategories = $this->getLinkedCategories($query);
        }

        // Log the search action
        Queue::push(new SaveSearch($query, auth()->id(), $request));

        return view('search.index', compact('ads', 'shops', 'linkedCategories', 'query', 'type', 'request'));
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
        $categoryAssociations = json_decode('{"1":["bike","bikes","bicyle","bicycles","vehicle","vehicles","car","cars","motorbike","motorbikes","motorcycle","motorcycles","caravan","caravans","trailer","trailers","camper","campers","van","vans","boat","boats","helmet","helmets","cycling helmet","plane","planes","helicopter","commercial vehicle","commercial vehicles","utility vehicle","utility vehicles","quad bike","scooter","scooters","cab","cabs","cabriolet","cabriolets","convertible car","convertible cars","truck","trucks","parts"],"8":["real estate","property","house","houses","apartment","apartments","flat","flats","rental","flat rental","apartment rental","house rental","flat-sharing","flat-sharings","flatsharing","flatsharings","flat sharing","flat sharings","home-sharing","home-sharings","homesharing","homesharings","home sharing","home sharings","house-sharing","house-sharings","housesharing","housesharings","house sharing","house sharings","holiday rental","office","offices","shop","shops","commercial","land","residential"],"14":["multimedia","informatics","computer","computers","IT","console","consoles","video games","video game","image","images","sound","phone","phones","television","televisions","TV","TVs","Radio","Radios","Tablet","Tablets","mp3","player","players","speaker","speakers","hifi","smartphone","smartphones","3D","telephony","phone","phones","laptop","laptops","computer","computers","mac","drones","drone","smart watch","smart watches","cell phone","cell phones","reader","readers","connector","connectors","printer","printers","headphone","headphones","electronics"],"19":["furniture","piece of furniture","pieces of furniture","decoration","interior design","home decor","household supplies","ornamentation","embellishment","white goods","household electrical","household electrical good","household electrical goods","domestic electrical","domestic electrical good","domestic electrical goods","household appliances","DIY","do it yourself","do-it-yourself","home improvement","garden","gardening","luggage"],"25":["fashion","clothes","clothing","shoe","shoes","accessory","accessories","men\'s accessories","mens accessories","men accessories","women\'s accessories","womens accessories","women accessories","men\'s clothing","mens clothing","men clothing","women\'s clothing","womens clothing","women clothing","men\'s shoes","mens shoes","men shoes","women\'s shoes","womens shoes","women shoes","man accessories","man clothing","man shoes","woman accessories","woman clothing","woman shoes","baby","baby clothing","baby gear","toddler","toddler clothing","kids","kids clothing","kids shoes","kid clothing","kid shoes","women handbag","women bag","woman handbag","woman bag","vintage"],"31":["jewelry","watch","watches","women\'s jewelry","women s jewelry","men\'s jewelry","men s jewelry","engagement","wedding"],"32":["beauty","perfume","perfumes","hair care","make up","make-up","makeup","fragrances","hair","skin care","care","health"],"33":["job","jobs","contract","part time","part-time","part time job","part-time job","full time","full-time","full time job","full-time job","offer","seasonal","event","events","temporary","tepmorary work","replacement","interim"],"38":["services","service","people care"],"39":["car sharing","carsharing","car share","carshare","car pool","carpool","car rental"],"40":["hobby","hobbies","toy","toys","figures","educational","model train","model trains","models train","models trains","models","kit","kits","slot car","slot cars","collectible","collectibles","dvd","dvds","cd","cds","fitness","running","yoga","sports","indoor games","fishing","golf","music","book","books","catalog","catalogs"],"47":["professional equipment","agriculture","agricultural","agricultural equipment","shipment","shipping","handling","loading equipment","renovation works","heavy equipment","construction","construction industry","tools","equipment","restaurant","catering","hotel","hotel trade","supplies","office supplies","business","businesses","medical","medical equipment","manufacturing","packing","industry","industrial"],"56":["wine","gastronomy","local produce","local produces","bottle","bottles"],"57":["art","culture","painting","paintings","photograph","photographs","picture","pictures","sculpture","carving","wood carving","cinema","movies","movies","concert","concerts","ticket","tickets","concert ticket","concert tickets","theater","theater ticket","theater tickets"]}');

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
