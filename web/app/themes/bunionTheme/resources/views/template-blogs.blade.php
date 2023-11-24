{{--
  Template Name: Resource Template
--}}
@section('content')
    @while (have_posts())
        @php(the_post())

        <div class="offset-content">
            <div class="news-column-grid">
                <div>
                    <h2>{{ the_title() }}</h2> {{-- Display the post title --}}
                    <div class="post-content">
                        {{ the_content() }} {{-- Display the post content --}}
                    </div>
                </div>
                <div>
                </div>
                <div></div>
            </div>
        </div>
    @endwhile
@endsection
