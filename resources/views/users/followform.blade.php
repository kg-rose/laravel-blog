{{-- 首先确定不是在看自己的个人信息 --}}
@if ($user->id !== Auth::user()->id)
    <div id="follow_form">
        {{-- 关注则显示取消关注按钮 --}}
        @if (Auth::user()->isFollowing($user->id))
            <form action="{{ route('followers.destroy', $user->id) }}" method="post">
                {{ csrf_field() }} {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-sm">取消关注</button>
            </form>

        {{-- 没关注就显示关注按钮 --}}
        @else
            <form action="{{ route('followers.store', $user->id) }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-sm btn-primary">关注</button>
            </form>
        @endif
    </div>
@endif