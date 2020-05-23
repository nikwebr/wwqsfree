@extends('front.layout.main')

@section('content')


<!-- Full Width Container -->
<div class="container-fluid">
  <div class="row">
      <!-- Main Container -->
      <div class="container">

          <!-- logo container -->
          <div class="row">
              <div class="col-md-offset-3 col-md-6">
                  <div class="logo-container">
                      <a href="/">
                      <img src="{{ (setting('site_logo')) ? setting('site_logo') : '/img/main-logo.png' }}" class="logo img-responsive" />
                      </a>
                      <h3 class="subtitle">SEND FILES TO ANYONE. FAST & FREE.</h3>
                  </div>
              </div>
          </div>
          <!-- logo container ends-->

          <!-- main data container -->
          <div class="row last-bottom-gap">
              <div class="col-md-2 col-xs-1"></div>
              <div class="col-md-8 col-xs-10">

                  <div class="row">
                    <ul class="list-unstyled zipfile-page-nav">
                        <li class="active">
                          <a href="/about">ABOUT</a>
                        </li>
                        <li>
                          <a href="/help">HELP</a>
                        </li>
                        <li>
                          <a href="/legal">LEGAL</a>
                        </li>
                    </ul>
                  </div>

                  <div class="row zipfile-data-container-page">

                    @if( setting('about_page') )
                      {!! setting('about_page') !!}
                    @else

                    <h1>About ZipFileMe</h1>

                    <p>
                      ZipFileMe is a secure, easy and free way to send your files to anyone, anywhere.
                      <br><br>
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id sem nec leo fringilla elementum. Duis laoreet dapibus arcu vitae suscipit. Cras tristique erat eget augue condimentum, in fringilla velit eleifend. Donec maximus purus eget elit hendrerit, a accumsan est tempus. Duis at tincidunt nisi. Quisque sit amet metus felis. Nulla finibus lorem eu ex scelerisque lobortis. Morbi tempor purus massa, eu suscipit turpis ornare a. Nunc ac mi ac lorem rhoncus venenatis. Ut sit amet semper justo.
                      <br><br>
                      Curabitur ultricies sem sed sagittis vulputate. Nulla in orci pellentesque
                      <br><br>
                      You can reach us at <a href="mailto:hello@zipfile.me">hello@example.me</a> with any questions or feedback.
                    </p>

                    @endif


                  </div>

              </div>
              <div class="col-md-2 col-xs-1"></div>
          </div>

      </div>
      <!-- Main Container ends -->
  </div>
</div>
<!-- Full Width Container -->
@endsection

@section('custome-js')
@endsection
