<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function __construct()
    {
        // 10分钟内只能调用3次
        $this->middleware('throttle:3,10', [
            'only'  => ['showLinkRequestForm']
        ]);
    }


    public function showLinkRequestForm()
    {
        return view('passwords.email');
    }

    /**
     * 验证发送邮件
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        $user = User::where(['email' => $email])->first();
        if (is_null($user)) {
            session()->flash('danger', '邮箱未注册!');
            return redirect()->back()->withInput();
        }

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        // 5. 入库，使用 updateOrInsert 来保持 Email 唯一
        DB::table('password_resets')->updateOrInsert(['email' => $email], [
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => new Carbon,
        ]);


        Mail::send('emails.reset_link', compact('token'), function ($message) use ($email) {
            $message->to($email)->subject("忘记密码");
        });

        session()->flash('success', '邮件已发送,请注意查收!');
        return redirect()->back();
    }

    /**
     * 填写邮箱密码页面
     * @param $token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showResetForm($token)
    {
        return view('passwords.reset', compact('token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password'  => 'required|confirmed|min:6'
        ]);
        $email = $request->email;
        $token = $request->token;
        $password = $request->password;
        $expires = 60*10;

        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            session()->flash('danger', '邮箱不存在');
            return redirect()->back()->withInput();
        }

        $record = (array) DB::table('password_resets')->where('email', $email)->first();
        if ($record) {
//            var_dump($record);die;
            // 5.1. 检查是否过期
            if (Carbon::parse($record['created_at'])->addSeconds($expires)->isPast()) {
                session()->flash('danger', '链接已过期，请重新尝试发送注册连接');
                return redirect()->route('password.request');
            }

            // 5.2. 检查是否正确
            if ( ! Hash::check($token, $record['token'])) {
                session()->flash('danger', '令牌错误');
                return redirect()->back();
            }

            // 5.3. 一切正常，更新用户密码
            $user->update(['password' => bcrypt($request->password)]);

            // 5.4. 提示用户更新成功
            session()->flash('success', '密码重置成功，请使用新密码登录');
            return redirect()->route('login');
        }

        // 6. 记录不存在
        session()->flash('danger', '未找到重置记录');
        return redirect()->back();
    }
}
