@extends('layout')

@section('content-class', 'dashboard')

@section('content')

    <div class="flexy mb-3">
        <h1 class="fill">{{ trans('addons.Heartbeats::cp.title') }}</h1>
        @can('super')
            <a href="{{ route('heartbeats.create') }}" class="btn btn-primary">{{ trans('addons.Heartbeats::cp.create_heartbeat') }}</a>
        @endcan
    </div>

    @if($heartbeats->isEmpty())
        <div class="card">
            <div class="no-results">
                <span class="icon icon-heart"></span>
                <h2>{{ trans('addons.Heartbeats::cp.title') }}</h2>
                <h3>{{ trans('addons.Heartbeats::cp.heartbeats_empty') }}</h3>
                @can('super')
                    <a href="{{ route('heartbeats.create') }}" class="btn btn-default btn-lg">{{ trans('addons.Heartbeats::cp.create_heartbeat') }}</a>
                @endcan
            </div>
        </div>
    @endif

    @if(false === $heartbeats->isEmpty())
        <div class="card flush">
            <table class="dossier">
                <tbody>
                @foreach($heartbeats as $heartbeat)
                    <tr>
                        <td class="cell-title">
                            <div class="stat" style="min-width: auto;">
                                <span class="icon icon-heart"></span>
                            </div>
                            <a href="{{ route('heartbeats.edit', $heartbeat->getId()) }}">{{ $heartbeat->getName() }}</a>
                        </td>
                        <td style="text-align: right; padding-right: 30px;">
                            <a href="{{ route('heartbeats.delete', $heartbeat->getId()) }}"><span class="icon icon-squared-cross" style="font-size: 15px;"></span>&nbsp;{{ trans('addons.Heartbeats::cp.delete_heartbeat') }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
