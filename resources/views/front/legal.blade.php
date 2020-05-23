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
                        <li >
                          <a href="/about">ABOUT</a>
                        </li>
                        <li>
                          <a href="/help">HELP</a>
                        </li>
                        <li class="active">
                          <a href="/legal">LEGAL</a>
                        </li>
                    </ul>
                  </div>

                  <div class="row zipfile-data-container-page">

                    @if( (setting('legal_page')) )
                      {!! setting('legal_page') !!}
                    @else

                    <h1>Terms and Conditions</h1>

                    <p>
                      Donec luctus dui eget orci dapibus vestibulum. Aliquam tristique sit amet ante non semper. Donec felis dolor, auctor vitae libero a, varius dapibus ligula. Donec lacinia tincidunt tellus eu blandit. Ut gravida orci eu dignissim euismod. Curabitur commodo eros mauris, sit amet bibendum tortor maximus quis. Cras at arcu mi. Mauris vitae fermentum nisi.
                      <br><br>
                      Aliquam id imperdiet erat, at molestie nisi. Aliquam erat volutpat. Morbi eget nibh orci. Mauris tristique diam est, eu venenatis tellus malesuada ut. Aenean sagittis neque sit amet quam convallis, quis elementum nisl posuere. In sagittis, sem vitae mattis laoreet, nulla orci mattis felis, et commodo lectus ante ac dolor. Nullam fermentum diam eu pulvinar consequat.
                      <br><br>
                      Aliquam sed vehicula felis. Vivamus at luctus orci. Aliquam erat volutpat. Cras sit amet pulvinar odio. Curabitur consequat, nisi ac maximus commodo, magna quam eleifend turpis, sed aliquet nunc mauris eget mauris. Donec dictum, mi ac dignissim vulputate, ante leo consequat velit, id viverra erat urna in purus. Donec eu tincidunt nulla, quis aliquam erat. Suspendisse eget augue cursus, faucibus lacus et, luctus tortor. Mauris interdum eu ante sit amet scelerisque. In a nibh eu nisi fringilla laoreet. Suspendisse laoreet egestas libero, vitae scelerisque elit suscipit sed. Maecenas laoreet sollicitudin velit at dapibus. Nulla ac interdum massa, sed varius ipsum.
                      <br><br>
                      Nam pretium, velit a cursus porta, felis nulla luctus mauris, eget semper urna sem nec mauris. Pellentesque venenatis fermentum accumsan. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc id rhoncus justo. Aliquam quis pharetra felis. Fusce fermentum et tellus a malesuada. Vestibulum tempus efficitur justo dictum euismod. Aliquam porttitor tristique quam, vel tempus erat varius a. Donec commodo nulla leo, at rutrum justo dapibus eget. Etiam sed rutrum diam, sed condimentum lacus. In et molestie sem. Nulla facilisi. Fusce scelerisque ligula arcu. Nulla in enim non tellus tincidunt pellentesque. Sed non nisl ut ex cursus faucibus aliquet congue nibh.
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
