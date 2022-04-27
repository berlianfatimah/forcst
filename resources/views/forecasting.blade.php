@extends('layouts/master')
@section('content')


    <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Forecasting</h4>
            <form class="form-sample" action="/calculate" method="POST">
                @csrf
              <p class="card-description">
                Silahkan pilih Data Aktual dan Metode Peramalan yang akan digunakan:
              </p>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Provinsi</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="provinsi">
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->province }}</option>
                            @endforeach
                    </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Metode</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="metode">
                            <option value="SES">Single Exponential Smoothing</option>
                            <option value="DES">Double Exponential Smoothing</option>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success mb-2">Forecast</button>    
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
    
@endsection