# Short-URL-with-tk
这个是利用Freenom API制作的TK域名的缩短链接，但是Freenom API似乎对免费域名关闭了。

This was designed to shorten URL using Freenom random domain API, however, it seems to be closed.

# 使用说明
修改 `config.php` 中的内容

`POST` 或 `GET` 请求 `api.php` ，参数为 `longurl` 即为需要缩短的链接。

返回JSON

## 当前情况
大概率会这样返回

`{"status":"failed","code":"203","message":"FREE domain registrations by API are not supported"}`
