@extends('layouts.master')

@section('content')
    <style>
        .scrollable { max-height: 450px; width: 100%; margin: 0; overflow-y: auto; margin-top: -21px; }
        .table-scrollable { margin: 0; padding: 0; }
    </style>
    <div class="col-sm-8">
        <h1>Annonces</h1>
        <div class="row">
            <div class="col-xs-4 col-sm-2 text-center" title="Annonces en ligne / Annonces supprimées">
                <h1><i class="fa fa-file"></i></h1>
                <span class="text-muted">Total/suppr.</span>
                <h4>{{ count($ads) }} <small>({{ count($deletedAds) }})</small></h4>
            </div>
            <div class="col-xs-4 col-sm-2 text-center" title="Vendues ces dernières 24h / Vendues en moyenne par jour">
                <h1><i class="fa fa-money"></i></h1>
                <span class="text-muted">-24h/moy.</span>
                <h4>{{ $last24hours['adSold'] }} <small>({{ $averages['adSold'] }})</small></h4>
            </div>
            <div class="col-xs-4 col-sm-2 text-center" title="Annonces créées ces dernières 24h / Annonces créées en moyenne par jour">
                <h1><i class="fa fa-pencil-square-o"></i></h1>
                <span class="text-muted">-24h/moy.</span>
                <h4>{{ $last24hours['ads'] }} <small>({{ $averages['adCreated'] }})</small></h4>
            </div>
            <div class="col-xs-4 col-sm-2 text-center" title="Annonces vues ces dernières 24h / Annonces vues en moyenne par jour">
                <h1><i class="fa fa-eye"></i></h1>
                <span class="text-muted">-24h/moy.</span>
                <h4>{{ $last24hours['adView'] }} <small>({{ $averages['adViewed'] }})</small></h4>
            </div>
            <div class="col-xs-4 col-sm-2 text-center" title="Nombre total de ventes / Montant total des ventes">
                <h1><i class="fa fa-eur"></i></h1>
                <span class="text-muted">Ventes/total</span>
                <h4>{{ count($soldAds) }} <small>({{ $soldAds->sum('amount') }} @lang('common.currency_symbol'))</small></h4>
            </div>
            <div class="col-xs-4 col-sm-2 text-center" title="Profils créés ces dernières 24h / Profils créés en moyenne par jour">
                <h1><i class="fa fa-user"></i></h1>
                <span class="text-muted">-24h/moyenne</span>
                <h4>{{ $last24hours['users'] }} <small>({{ $averages['users'] }})</small></h4>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <h1>Divers</h1>
        <div class="row placeholders">
            <div class="col-xs-3 text-center">
                <h1><i class="fa fa-dollar"></i></h1>
                <span class="text-muted">Comptes stripe</span>
                <h4>{{ count($stripeProfiles) }}</h4>
            </div>
            <div class="col-xs-3 text-center">
                <h1><i class="fa fa-search"></i></h1>
                <span class="text-muted">Rech. partagées</span>
                <h4>{{ count($searches) }}</h4>
            </div>
            <div class="col-xs-3 text-center">
                <h1><i class="fa fa-eye"></i></h1>
                <span class="text-muted">Vues de profil</span>
                <h4>{{ count($statsUser) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4 table-responsive">
                <h2 class="sub-header">Utilisateurs <small>{{ $users->count() }}</small></h2>
                <h5 class="text-muted">Position ({{ $userGeolocated->count() }} remplies : {{ round($userGeolocated->count() / $users->count(), 2)  }}% de complétion)</h5>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width:20%">Inscription</th>
                        <th style="width:15%">Name</th>
                        <th style="width:15%">Ajo-Ven-Sup</th>
                    </tr>
                    </thead>
                </table>
                <div class="scrollable">
                    <table class="table table-hover table-striped table-condensed table-scrollable">
                        <tbody>
                            @foreach ($usersSubset as $user)
                                @if ($user->trashed())
                                    <tr class="warning">
                                        <td style="width:20%">{!! HTML::time($user->created_at) !!}</td>
                                        <td style="width:15%">{{ $user->present()->fullName() }}</td>
                                        <td style="width:15%">{{ $user->total_ads.'-'.$user->sold_ads.'-'.$user->deleted_ads }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="width:20%">{!! HTML::time($user->created_at) !!}</td>
                                        <td style="width:15%">{{ $user->present()->fullName() }}</td>
                                        <td style="width:15%">{{ $user->total_ads.'-'.$user->sold_ads.'-'.$user->deleted_ads }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-sm-5 table-responsive">
                <h2 class="sub-header">Discussions <small>{{ $discussions->count() }}</small></h2>
                <h5>&nbsp;</h5>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Commencée</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
                <div class="scrollable">
                    <table class="table table-hover table-striped table-condensed table-scrollable">
                        <tbody>
                        @foreach ($discussionsSubset as $discussion)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($discussion->created_at)->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('profiles.show', $discussion->theUser) }}">{{ $discussion->theUser->present()->fullName() }}</a>
                                </td>
                                <td>
                                    <i class="fa fa-exchange text-muted"></i>
                                </td>
                                <td>
                                   {{ $discussion->theOther->present()->fullName() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-3 table-responsive">
                <h2 class="sub-header">Recherches <small>{{ $statsSearch->count() }}</small></h2>
                <h5>&nbsp;</h5>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Term</th>
                    </tr>
                    </thead>
                </table>
                <div class="scrollable">
                    <table class="table table-hover table-striped table-condensed table-scrollable">
                        <tbody>
                            @foreach ($searchesSubset as $search)
                                <tr>
                                    <td>
                                        @if (isset($search->hash))
                                            <a href="{{ route('profiles.show', $search->hash) }}">{{ $search->term }}</a>
                                        @else
                                            {{ $search->term }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop


