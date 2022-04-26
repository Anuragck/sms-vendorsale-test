
    @extends('layouts.main')
    @section('main-content')



<router-view :user="{{json_encode($user)}}"></router-view>







    @endsection

