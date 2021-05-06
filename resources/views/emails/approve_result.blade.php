{{ $receiver }}様
<br />
<br />
ご利用いただき、ありがとうございます。
<br />

@if ($status === 1)
    おめでとうございます！ {{ $approver }} リクエストが承認されました <b>{{ $aprroval }}</b>。
@else
    申し訳ありません。 {{ $approver }} リクエストが拒否されました <b>{{ $aprroval }}</b>。
@endif

<br />
<br />
"{{ $msg }}"

<br />
<br />
今後ともよろしくお願いいたします！
<br />
KDC - 電子決裁システム。
