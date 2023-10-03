@extends('layouts.app')

@section('content')
    <livewire:products.product-filter :uniqueVariations="$uniqueVariations" />
@endsection
