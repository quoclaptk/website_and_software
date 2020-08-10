<div class="content_main">
    <div class="row">
        <div class="col-md-3 hidden-xs hidden-sm">
            <div class="block-ads-left">
                <a href="#" target="_blank">
                    <img src="{{ url('frontend/images/euro_2016.jpg') }}" />
                </a>
            </div>
            <div class="block-ads-left">
                <a href="#" target="_blank">
                    <img src="{{ url('frontend/images/euro_2016_1.jpg') }}" />
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="header"><h1 class="title">{{ title_bar }}</h1></div>
            
            <div class="table-responsive">
              <div class="box_euro_live">{{ schedule_info }}</div>
            </div>
            <div class="like_button">
                <div class="fb-like" data-href="{{ router.getRewriteUri() }}" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
            </div>
        </div>
    </div>
</div>
