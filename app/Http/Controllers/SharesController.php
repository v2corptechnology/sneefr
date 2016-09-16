<?php namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Models\Ad;
use Sneefr\Repositories\Ad\AdRepository;

class SharesController extends Controller
{
    /**
     * Redirect to the sharing screen.
     *
     * @param int                                  $adId
     * @param \Illuminate\Http\Request             $request
     * @param \Sneefr\Repositories\Ad\AdRepository $adRepository
     *
     * @return string
     */
    public function shareAd(int $adId, Request $request, AdRepository $adRepository)
    {
        $ad = $adRepository->find($adId);

        $shareUrl = $this->getSharingUrl($ad, $request->get('redirect_uri'));

        if ($request->ajax()) {
            return view('partials.modals._share', compact('ad', 'shareUrl'));
        }

        return redirect($shareUrl);
    }

    /**
     * Generate the dynamic sharing URL on social media.
     *
     * @param \Sneefr\Models\Ad $ad
     * @param string|null       $redirectUri
     *
     * @return string
     */
    private function getSharingUrl(Ad $ad, string $redirectUri = null) : string
    {
        $baseUrl = route('ad.show', $ad);

        $redirectUri = $redirectUri ?? $baseUrl . '?ad=' . $ad->getId();

        $shareRequestBody = [
            'app_id'       => config('sneefr.keys.FACEBOOK_CLIENT_ID'),
            'display'      => 'popup',
            'caption'      => $ad->getTitle(),
            'description'  => $ad->oneLineDescription(),
            'link'         => $baseUrl . '?utm_source=facebook&utm_medium=ad_sharer&utm_campaign=ad',
            'redirect_uri' => $redirectUri,
            'picture'      => $ad->firstImageUrl('1200x630', false),
        ];

        return 'https://www.facebook.com/dialog/feed?' . http_build_query($shareRequestBody, null, '&');
    }
}
