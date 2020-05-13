@extends('co_accounts.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">#{{$account->id}} - {{$account->full_name}}</div>
                    <div class="card-body">
                        <a href="{{route('co_accounts.home')}}" class="btn btn-success">الرجوع للإشتراكات</a>
                        <hr>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div>
                            <div class="form-group row">
                                <label for="acId" class="col-sm-2 col-form-label">ID</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="acId" value="{{$account->id}}">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group row">
                                <label for="acName" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="acName"
                                           value="{{$account->full_name}}">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group row">
                                <label for="acPhone" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="acPhone"
                                           value="{{$account->phone}}">
                                </div>
                            </div>
                        </div>
                        @if($account->subscription)
                            <div>
                                <div class="form-group row">
                                    <label for="acPhone" class="col-sm-2 col-form-label">Max devices</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control" id="acPhone"
                                               value="{{implode(' ', [$account->devices->count(), '/', $account->subscription->max_devices])}}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group row">
                                    <label for="acSubs" class="col-sm-2 col-form-label">Subscription expire at</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly
                                               class="form-control {{(!is_null($account->subscription->expire_at) && now()->startOfDay()->greaterThan($account->subscription->expire_at)) ? 'is-invalid text-danger' : 'text-success'}}"
                                               id="acSubs" value="{{ !$account->subscription->expire_at ? 'إشتراك مدى الحياة' :
                                                    $account->subscription->expire_at->toFormattedDateString()}}">
                                    </div>
                                </div>
                            </div>
                            @if($account->subscription->expire_at)
                                <form action="{{route('co_accounts.account.subscription.update', $account)}}"
                                      method="post">
                                    {!! csrf_field() !!}
                                    <hr>
                                    <h3>تمديد / تجديد الإشتراك</h3>
                                    <div class="form-group">
                                        <label for="SubsPeriod">المدة</label>
                                        <select class="form-control" id="SubsPeriod" name="period">
                                            <option value="day">يوم</option>
                                            <option value="week">أسبوع</option>
                                            <option value="2week">أسبوعين</option>
                                            <option value="3week">3 أسابيع</option>
                                            <option value="month">شهر</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" data-toggle="confirmation"
                                            data-btn-ok-label="تاكيد" data-btn-ok-class="btn-success"
                                            {{--data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"--}}
                                            data-btn-cancel-label="لا" data-btn-cancel-class="btn-danger"
                                            {{--data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"--}}
                                            data-title="تاكيد ؟" data-content="تاكيد تمديد / تجديد الإشتراك ؟">Submit
                                    </button>
                                </form>
                            @endif

                            @if($devices->count() > 0)
                                <hr>
                                <h3>الأجهزة المرتبطة بالإشتراك</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">إسم الجهاز</th>
                                            <th scope="col">أخر أى بى</th>
                                            <th scope="col">أول نشاط</th>
                                            <th scope="col">أخر نشاط</th>
                                            <th scope="col">إجراءات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($devices as $device)
                                            <tr>
                                                <th scope="row">{{$loop->index + 1}}</th>
                                                <th>{{$device->device_name}}</th>
                                                @if($device->trashed())
                                                    <th colspan="4">
                                                        <center>
                                                            محذوف في :
                                                            {{$device->deleted_at->timezone(config('app.timezone'))->toDayDateTimeString()}}
                                                        </center>
                                                    </th>
                                                @else
                                                    <th>{{count($device->ips) > 0 ? $device->ips[count($device->ips) - 1] : 'غير معروف'}}</th>
                                                    <th>{{$device->created_at ? $device->created_at->timezone(config('app.timezone'))->toDayDateTimeString() : 'غير معروف'}}</th>
                                                    <th class="text text-{{ now()->lessThan($device->last_activity->addMinutes(5)) ? 'success' : 'danger'}}">
                                                        {{$device->last_activity ? $device->last_activity->timezone(config('app.timezone'))->toDayDateTimeString() : 'غير معروف'}}
                                                    </th>
                                                    <th>
                                                        <a type="submit" class="btn btn-danger"
                                                           data-toggle="confirmation"
                                                           href="{{URL::signedRoute('co_accounts.account.subscription.device.delete', [$account, $account->subscription, $device])}}"
                                                           data-btn-ok-label="تاكيد" data-btn-ok-class="btn-danger"
                                                           {{--data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"--}}
                                                           data-btn-cancel-label="لا"
                                                           data-btn-cancel-class="btn-secondary"
                                                           {{--data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"--}}
                                                           data-title="تاكيد الحذف ؟"
                                                           data-content="تاكيد حذف الجهاز ؟">حذف</a>
                                                    </th>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            <form action="{{route('co_accounts.account.subscription.store', $account)}}" method="post">
                                {!! csrf_field() !!}
                                <hr>
                                <h3>إضافة إشتراك للحساب</h3>
                                <div class="form-group">
                                    <label for="maxDevices">عدد الاجهزة</label>
                                    <input type="number" class="form-control" max="20" min="1" name="max_devices"
                                           value="1" id="maxDevices">
                                </div>
                                <div class="form-group">
                                    <label for="SubsPeriod">المدة</label>
                                    <select class="form-control" id="SubsPeriod" name="period">
                                        <option value="day">يوم</option>
                                        <option value="week">أسبوع</option>
                                        <option value="2week">أسبوعين</option>
                                        <option value="3week">3 أسابيع</option>
                                        <option value="month">شهر</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" data-toggle="confirmation"
                                        data-btn-ok-label="تاكيد" data-btn-ok-class="btn-success"
                                        {{--data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"--}}
                                        data-btn-cancel-label="لا" data-btn-cancel-class="btn-danger"
                                        {{--data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"--}}
                                        data-title="تاكيد ؟" data-content="تاكيد إضافة الإشتراك ؟">Submit
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{--<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>--}}
    <script src="{{ asset('js/bootstrap-confirmation.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle=confirmation]').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                // other options
            });
        })
    </script>
@endpush
