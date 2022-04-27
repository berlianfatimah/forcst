@extends('layouts/master')
@section('content')

<div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-12 col-xl-8 mb-7 mb-xl-0">
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
            <h4 class="card-title">Tambah Data Aktual</h4>
            <form class="forms-sample" method="POST" action="/actual">
              @csrf
              <div class="form-group row">
                <label for="provinsi" class="col-sm-3 col-form-label">Provinsi</label>
                <div class="col-sm-9">
                  <select class="form-control" name="provinsi" id="provinsi">
                    @foreach ($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->province }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="aktual" class="col-sm-3 col-form-label">Data Aktual</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="aktual" name="aktual" placeholder="Data Aktual" required>
                </div>
              </div>
              <button type="submit" class="btn btn-success mr-2">Tambah Data</button>
              <button class="btn btn-light">Cancel</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Tabel Produksi Padi</h4>
                @if(session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>   
                @endif
            <p class="card-description">
              Data Aktual Produksi Padi
            </p>
            <form class="form-sample" action="/actual" method="">
              @csrf
            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  {{-- <label class="col-sm-3 col-form-label">Provinsi</label> --}}
                  <div class="col-sm-12">
                      <select class="form-control" name="provinsi">
                          @foreach ($provinces as $province)
                              <option value="{{ $province->id }}">{{ $province->province }}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                  <button type="submit" class="btn btn-success mb-2">Search</button>    
              </div>
            </div>
          </form>
            <div class="table-responsive">
              <table id="" class="display expandable-table" style="width:100%">
                <thead>
                  <tr>
                    <th>Provinsi</th>
                    <th>Tahun</th>
                    <th>Produksi Padi</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($actuals as $actual)
                  <tr>
                    <td>{{ $actual->province->province }}</td>
                    <td>{{ $actual->year }}</td>
                    <td>{{ $actual->actual }}</td>
                    <td>
                      <a href="/actual/{{ $actual->id }}/edit" class="btn btn-primary btn-sm">edit</a>
                      <form action="/actual/{{ $actual->id }}" method="POST" class="d-inline">
                        <input type="submit" name="" id="" value="delete" class="btn btn-danger btn-sm">
                        @csrf
                        @method('DELETE')
                      </form>
                      {{-- <a href="" class="btn btn-danger btn-sm">delete</a> --}}
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    
@endsection