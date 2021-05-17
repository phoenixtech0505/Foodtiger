@extends('layouts.front', ['class' => ''])

@section('content')
   <section class="section">
    <div class="container">
        <br /><br />
        <div class="alert alert-danger" role="alert">
            Install is ok. But looks like you are running the site under subdomain. 
        </div>
        <p>
            When you run the site in subdomain, you need to declare that subdomain in the .env file<br /><br />
        <pre>IGNORE_SUBDOMAINS="www,{{ $subdomain }}"</pre><br /><br />
        <a href="https://mobidonia.gitbook.io/mresto/docs/environment-configuration#7-subdomain" type="button" class="btn btn-success">Read more in the docs</a>
        </p>
    </div>
   </section>
@endsection