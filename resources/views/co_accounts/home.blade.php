@extends('co_accounts.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">قائمة الإشتراكات</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form style="margin-bottom: 30px;" action="{{route('co_accounts.account.store')}}"
                              method="post">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="full_name">name</label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" autocomplete="off"
                                       class="form-control @error('full_name') is-invalid @enderror" id="full_name">
                                @error('full_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" autocomplete="off"
                                       class="form-control @error('phone') is-invalid @enderror" id="phone">
                                {{--<small id="emailHelp" class="form-text text-muted">We'll never share your email with
                                    anyone else.
                                </small>--}}
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" autocomplete="off"
                                       class="form-control @error('password') is-invalid @enderror" id="password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="cPassword">Confirm Password</label>
                                <input type="password" name="password_confirmation" autocomplete="off"
                                       class="form-control" id="cPassword">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        @if($accounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">إسم الحساب</th>
                                        <th scope="col">رقم التليفون</th>
                                        <th scope="col">عدد الأجهزة</th>
                                        <th scope="col">نهاية الإشتراك</th>
                                        <th scope="col">أخر نشاط</th>
                                        <th scope="col">إجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($accounts as $account)
                                        <tr>
                                            <th scope="row">{{$account->id}}</th>
                                            <td>{{$account->full_name}}</td>
                                            <td>{{$account->phone}}</td>
                                            @if($account->subscription)
                                                <td>{{ implode(' ', [$account->devices->count(), '/', $account->subscription->max_devices]) }}</td>
                                                <td class="text text-{{(!is_null($account->subscription->expire_at) && now()->startOfDay()->greaterThan($account->subscription->expire_at)) ? 'danger' : 'success'}}">
                                                    {{
                                                        $account->subscription->expire_at ?
                                                        $account->subscription->expire_at->toFormattedDateString()
                                                        : 'إشتراك مدى الحياة'
                                                    }}
                                                </td>
                                                @php($last_activity = $account->subscription->devices->count() > 0 ? $account->subscription->devices->max('last_activity') : null)
                                                <td>{{ $last_activity ?
                                                    $last_activity->timezone(app('timezone'))->toDayDateTimeString() :
                                                    'لا يوجد نشاط' }}</td>
                                            @else
                                                <td colspan="3">لا يوجد إشتراك</td>
                                            @endif
                                            <td>
                                                <a class="btn btn-primary" href="{{route('co_accounts.account.view', $account)}}">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
