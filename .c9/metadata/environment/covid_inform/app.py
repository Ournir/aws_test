{"filter":false,"title":"app.py","tooltip":"/covid_inform/app.py","undoManager":{"mark":2,"position":2,"stack":[[{"start":{"row":0,"column":0},"end":{"row":66,"column":38},"action":"insert","lines":["import os","","from flask import Flask, request, abort","from linebot import (","    LineBotApi, WebhookHandler",")","from linebot.exceptions import (","    InvalidSignatureError",")","from linebot.models import (","    MessageEvent, TextMessage, TemplateSendMessage, CarouselTemplate, CarouselColumn)","","app = Flask(__name__)","","LINE_CHANNEL_ACCESS_TOKEN = os.environ[\"LINE_CHANNEL_ACCESS_TOKEN\"]","LINE_CHANNEL_SECRET = os.environ[\"LINE_CHANNEL_SECRET\"]","","line_bot_api = LineBotApi(LINE_CHANNEL_ACCESS_TOKEN)","handler = WebhookHandler(LINE_CHANNEL_SECRET)","","","@app.route(\"/callback\", methods=['POST'])","def callback():","    signature = request.headers['X-Line-Signature']","","    body = request.get_data(as_text=True)","    app.logger.info(\"Request body: \" + body)","","    try:","        handler.handle(body, signature)","    except InvalidSignatureError:","        abort(400)","","    return 'OK'","","","@handler.add(MessageEvent, message=TextMessage)","def response_message(event):","    # notesのCarouselColumnの各値は、変更してもらって結構です。","    notes = [CarouselColumn(thumbnail_image_url=\"https://renttle.jp/static/img/renttle02.jpg\",","                            title=\"【ReleaseNote】トークルームを実装しました。\",","                            text=\"creation(創作中・考え中の何かしらのモノ・コト)に関して、意見を聞けるようにトークルーム機能を追加しました。\",","                            actions=[{\"type\": \"message\",\"label\": \"サイトURL\",\"text\": \"https://renttle.jp/notes/kota/7\"}]),","","             CarouselColumn(thumbnail_image_url=\"https://renttle.jp/static/img/renttle03.jpg\",","                            title=\"ReleaseNote】創作中の活動を報告する機能を追加しました。\",","                            text=\"創作中や考え中の時点の活動を共有できる機能を追加しました。\",","                            actions=[","                                {\"type\": \"message\", \"label\": \"サイトURL\", \"text\": \"https://renttle.jp/notes/kota/6\"}]),","","             CarouselColumn(thumbnail_image_url=\"https://renttle.jp/static/img/renttle04.jpg\",","                            title=\"【ReleaseNote】タグ機能を追加しました。\",","                            text=\"「イベントを作成」「記事を投稿」「本を登録」にタグ機能を追加しました。\",","                            actions=[","                                {\"type\": \"message\", \"label\": \"サイトURL\", \"text\": \"https://renttle.jp/notes/kota/5\"}])]","","    messages = TemplateSendMessage(","        alt_text='template',","        template=CarouselTemplate(columns=notes),","    )","","    line_bot_api.reply_message(event.reply_token, messages=messages)","","","if __name__ == \"__main__\":","    port = int(os.getenv(\"PORT\", 5000))","    app.run(host=\"0.0.0.0\", port=port)"],"id":1}],[{"start":{"row":14,"column":40},"end":{"row":14,"column":65},"action":"remove","lines":["LINE_CHANNEL_ACCESS_TOKEN"],"id":2},{"start":{"row":14,"column":40},"end":{"row":14,"column":177},"action":"insert","lines":["elCmOjEOxgJtquqLxOxwqP9Mc8lyeoTUCTaMfQXIQf08xz + zfZxxknuGBOHDoF0yuV65K / ZGEZpUdziNPox5fCQ + MZxArQ4M8fbQ4id95b / waBV / ledmcNun / JLIs"]}],[{"start":{"row":15,"column":34},"end":{"row":15,"column":53},"action":"remove","lines":["LINE_CHANNEL_SECRET"],"id":3},{"start":{"row":15,"column":34},"end":{"row":15,"column":66},"action":"insert","lines":["1f91aa2d304bc6bef1300f725891a9c6"]}],[{"start":{"row":15,"column":72},"end":{"row":15,"column":73},"action":"insert","lines":[" "],"id":6}],[{"start":{"row":15,"column":69},"end":{"row":15,"column":70},"action":"insert","lines":["g"],"id":5},{"start":{"row":15,"column":70},"end":{"row":15,"column":71},"action":"insert","lines":["i"]},{"start":{"row":15,"column":71},"end":{"row":15,"column":72},"action":"insert","lines":["t"]}],[{"start":{"row":15,"column":66},"end":{"row":15,"column":67},"action":"insert","lines":["g"],"id":4},{"start":{"row":15,"column":67},"end":{"row":15,"column":68},"action":"insert","lines":["i"]},{"start":{"row":15,"column":68},"end":{"row":15,"column":69},"action":"insert","lines":["t"]}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":15,"column":66},"end":{"row":15,"column":66},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1618994909059,"hash":"84548b55bcf8538785a7a65718221d639061cec3"}