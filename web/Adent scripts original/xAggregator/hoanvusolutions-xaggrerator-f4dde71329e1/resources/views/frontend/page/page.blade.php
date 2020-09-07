@extends('frontend.layouts.main')
@section('content')
<div class="content">
    <div class="container">
        <div class='cover-static-page'>
            <h2><?php echo $page->title; ?></h2>
            <div class='content-static-page'>
                <?php echo $page->content; ?>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    
@endsection