@extends('layouts.master')

@section('title', 'TODO')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h1 class="panel-title">Deal history</h1>
                </div>

                <div class="panel-body">
                    <p>Here are all the deals you concluded, latest first.</p>
                </div>

                <!-- Table -->
                <table class="table table-striped table-hover">
                    <thead></thead>
                    <tbody>
                        @forelse($deals as $deal)

                            <?php $isSale = $deal->seller->id == auth()->id();?>

                            <tr class=" {{ $isSale ? 'success' : null }}">
                                <td>{!! HTML::time($deal->created_at) !!}</td>
                                <td>
                                    <a href="{{ route('items.show', $deal->ad) }}"
                                       title="{{ $deal->ad->present()->title() }}">{{ $deal->ad->present()->title() }}</a>
                                </td>
                                <td>{{ $deal->details['charge']['price'] }}</td>
                                <td>{{ $deal->details['details']['quantity'] }}</td>
                                <td>
                                    @if ($isSale)
                                        {{ $deal->buyer->present()->fullName() }}
                                    @else
                                        <a href="{{ route('shops.show', $deal->ad->shop) }}" title="{{ $deal->ad->shop->getName() }}">{{ $deal->ad->shop->getName() }}</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($isSale)
                                        {{ $deal->buyer->getEmail() }}
                                    @else
                                        {{ $deal->seller->getEmail() }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="warning">
                                <td colspan="6">You have no deal yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
