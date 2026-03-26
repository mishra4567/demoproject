@extends('admin.layout.layout')
@section('page_title', 'Color Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Color</h3>
            <a href="{{ route('color') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Color</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('color.manage_color_process') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="color" class="control-label mb-1">Color</label>
                                        <input id="color" name="color" value="{{ $color }}" type="text"
                                            class="form-control" required>
                                    </div>

                                    {{-- <div class="mb-3">
                                    <label for="cc-number" class="control-label mb-1">Card number</label>
                                    <input id="cc-number" name="cc-number" type="tel"
                                        class="form-control cc-number identified visa" value="" data-val="true"
                                        data-val-required="Please enter the card number"
                                        data-val-cc-number="Please enter a valid card number" autocomplete="cc-number">
                                    <span class="help-block" data-valmsg-for="cc-number" data-valmsg-replace="true"></span>
                                </div> --}}
                                    {{-- <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="cc-exp" class="control-label mb-1">Expiration</label>
                                            <input id="cc-exp" name="cc-exp" type="tel" class="form-control cc-exp"
                                                value="" data-val="true"
                                                data-val-required="Please enter the card expiration"
                                                data-val-cc-exp="Please enter a valid month and year" placeholder="MM / YY"
                                                autocomplete="cc-exp">
                                            <span class="help-block" data-valmsg-for="cc-exp"
                                                data-valmsg-replace="true"></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="x_card_code" class="control-label mb-1">Security
                                            code</label>
                                        <div class="input-group">
                                            <input id="x_card_code" name="x_card_code" type="tel"
                                                class="form-control cc-cvc" value="" data-val="true"
                                                data-val-required="Please enter the security code"
                                                data-val-cc-cvc="Please enter a valid security code" autocomplete="off">

                                        </div>
                                    </div>
                                </div> --}}
                                </div>
                                <div class="d-grid">
                                    <input type="hidden" name="id" value="{{ $id ?? '' }}">
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                        Submit
                                        {{-- <i class="fa-solid fa-lock fa-lg"></i>&nbsp; --}}
                                        {{-- <span id="payment-button-amount">Pay $100.00</span> --}}
                                        {{-- <span id="payment-button-sending" style="display:none;">Sending…</span> --}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
