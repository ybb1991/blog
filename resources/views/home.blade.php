@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
            <button-counter></button-counter>
            <button v-on:click="count++">You clicked me @{{ count }} times.</button>
            <!-- <p>@{{ count }}</p> -->
        </div>
    </div>
</div>
@end

@section('scripts')
<script>
  new Vue({ el: '#app' })
</script>
