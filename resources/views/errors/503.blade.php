@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('The application is currently in maintenance mode. Please try again later.'))
