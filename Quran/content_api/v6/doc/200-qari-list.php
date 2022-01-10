<div class="cbox" id='qari-list'>

  <h2 class="f" data-kerkere='#qari-list-detail' data-kerkere-icon='open'>
    <div class="cauto pRa10"><span class="badge primary">GET</span></div>
    <div>{%trans "Get list of qari detail"%}</div>
  </h2>
  <div id="qari-list-detail">

    <div class="msg url">
      <i class="method">GET</i>
      <span>{{apiURL}}<b>qari</b></span>
    </div>

    <h3>{%trans "cURL"%} <small>{%trans "example"%}</small></h3>
<pre>
curl -X GET {{apiURL}}qari
</pre>

<h3>{%trans "Response"%} <small>{%trans "example"%}</small></h3>
<pre>
{
  "ok": true,
  "result": [
    {
      "index": 1090,
      "lang": "fa",
      "type": "ترتیل",
      "addr": "https://dl.salamquran.com/ayat/parhizgar-murattal-48/",
      "slug": "parhizgar",
      "name": "شهریار پرهیزگار",
      "image": "https://salamquran.local/static/images/qariyan/parhizgar.png",
      "short_name": "پرهیزگار"
    },
    {
      "index": 1091,
      "lang": "fa",
      "type": "ترتیل",
      "addr": "https://dl.salamquran.com/ayat/mansouri-murattal-40/",
      "slug": "mansouri",
      "name": "کریم منصوری",
      "image": "https://salamquran.local/static/images/qariyan/mansouri.png",
      "short_name": "منصوری"
    }
  ]
}
</pre>

  </div>
</div>
