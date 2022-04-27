@extends('layouts/master')
@section('content')

<div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-normal">Welcome Berlian</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <img src="{{ asset('images/petani.jpg') }}" alt="" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Edit Data Aktual</h4>
            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>   
            @endif
            {{-- <p class="card-description">
              Tambah Data Aktual
            </p> --}}
            <form class="forms-sample" method="POST" action="/actual/{{ $actual->id }}">
              @csrf
              @method('PUT')
              <div class="form-group row">
                <label for="provinsi" class="col-sm-3 col-form-label">Provinsi</label>
                <div class="col-sm-9">
                {{-- <input type="text" class="form-control" id="provinsi" name="provinsi"  required value="{{ $posts->provinsi }}"> --}}
                  <select class="form-control" name="provinsi" id="provinsi" value="{{ $actual->provinsi }}" >
                    @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" {{ $actual->province_id == $province->id ? 'selected' : '' }}>{{ $province->province }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun" required value="{{ $actual->year }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="aktual" class="col-sm-3 col-form-label">Data Aktual</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="aktual" name="aktual" placeholder="Data Aktual" required value="{{ $actual->actual }}">
                </div>
              </div>
              <button type="submit" class="btn btn-success mr-2">Edit Data</button>
              <a href="/actual">back</a>
              {{-- <button class="btn btn-light">Back</button> --}}
            </form>
          </div>
        </div>
      </div>
      {{-- <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Tabel Produksi Padi</h4>
            <p class="card-description">
              Data Aktual Produksi Padi
            </p>
            <div class="table-responsive">
              <table id="" class="display expandable-table" style="width:100%">
                <thead>
                  <tr>
                    <th>Provinsi</th>
                    <th>Tahun</th>
                    <th>Produksi Padi</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($posts as $post)
                  <tr>
                    <td>{{ $post->provinsi }}</td>
                    <td>{{ $post->year }}</td>
                    <td>{{ $post->actual }}</td>
                    <td>
                      <a href="/posts/{{ $post->id }}/edit" class="btn btn-primary btn-sm">edit</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> --}}
    </div>
  </div>
    
@endsection