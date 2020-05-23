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
                          <img src="/img/main-logo.png" class="logo img-responsive"/>
                      </a>
                      <h3 class="subtitle">SEND FILES TO ANYONE. FAST & FREE.</h3>
                  </div>
              </div>
          </div>
          <!-- logo container ends-->

          <!-- main data container -->
            <div class="row">
                <div class="col-md-2 col-xs-1"></div>
                <div class="col-md-8 col-xs-10">
                  <div class="row">
                    <ul class="list-unstyled zipfile-page-nav">
                        <li>
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
                </div>
                <div class="col-md-2 col-xs-1"></div>
            </div>

          <!-- main data container -->
          <div class="row last-bottom-gap">
              <div class="col-md-offset-3 col-md-6">
                  <div class="zipfile-data-container">

                    <!-- File Download screen -->
                        <div class="sub-user-section">
                            <img src="/img/no-access.svg" class="download-icon">
                            <h3 class="sending-title">Time limit expired</h3>
                            <span class="upload-done-sub-text">{{count($downlaodData->files)}} files  •  {{ $downlaodData->fileSize }}  •  <span style="color:#c92c34;">Download time limit expired</span></span>
                            <ul class="list-unstyled download-filelist-container">
                                @foreach($downlaodData->files as $files)
                                <li class="download-file-item">
                                    <span class="download-file-name pull-left">{{$files->file_original_name}}</span>
                                    <a href="#" class="pull-right" disabled>
                                        <img src="/img/no-download.svg" class="download-icon-small">
                                    </a>
                                </li>
                                @endforeach

                            </ul>
                            <button type="button" class="btn btn-lg btn-block zfl-button last-bottom-gap" onclick="window.location.href='/'">SEND NEW FILES</button>
                        </div>
                    <!-- File Download screen Ends -->


                  </div>
              </div>
          </div>
          <!-- main data container -->

      </div>
      <!-- Main Container ends -->
  </div>
</div>
<!-- Full Width Container -->
@endsection