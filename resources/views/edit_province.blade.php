@extends('layouts/master')
@section('content')

<div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-normal">Welcome Berlian</h3>
      </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Tambah Data Provinsi</h4>
                {{-- <p class="card-description">
                  Tambah Data Aktual
                </p> --}}
                <form class="forms-sample" method="POST" action="/province/{{ $province->id }}">
                    @csrf
                    @method('PUT')
                  <div class="form-group row">
                    <label for="tahun" class="col-sm-3 col-form-label">Nama Povinsi</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" value="{{$province->province  }}" id="" name="provinsi" placeholder="Provinsi" required>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success mr-2">Edit Data</button>
                  <button class="btn btn-light">Cancel</button>
                </form>
              </div>
            </div>
        </div>
    </div>
  </div>
    
@endsection