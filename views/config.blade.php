@extends('admin.master')

@section('title', trans('BlessingSkin\ProxyIP::general.config.title'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ trans('BlessingSkin\ProxyIP::general.config.title') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin') }}">{{ trans('general.admin') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ trans('BlessingSkin\ProxyIP::general.config.title') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ trans('BlessingSkin\ProxyIP::general.config.title') }}</h3>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ trans('BlessingSkin\ProxyIP::general.config.description') }}</p>
                            {!! $form !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.querySelector('.test-proxy-ip').addEventListener('click', function() {
        fetch('{{ url('admin/api/proxyip/test') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.code === 0) {
                blessing.notify.toast('success', data.message);
            } else {
                blessing.notify.toast('error', data.message);
            }
        })
        .catch(error => {
            blessing.notify.toast('error', error.message);
        });
    });
</script>
@endsection
