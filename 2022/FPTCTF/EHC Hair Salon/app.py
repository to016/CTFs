import re
from flask import Flask, render_template_string, request

app = Flask(__name__)
regex = "request|config|self|class|flag|0|1|2|3|4|5|6|7|8|9|\"|\'|\\|\~|\%|\#"

#FPTUHacking{d4y_d1_0ng_ch4u_0i,ban_da_thoat_khoi_EHC_hair_salon_roi}

error_page = '''
        {% extends "layout.html" %}
        {% block body %}
        <center>
           <section class="section">
              <div class="container">
                 <h1 class="title">Ông cháu à!</h1>
                 <p>Ông chú chỉ cắt được quả đầu Tommy Xiaomi thôi!</p>
              </div>
           </section>
        </center>
        {% endblock %}
        '''


@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'POST':
        if not request.form['hair']:
            return render_template_string(error_page)

        if len(request.form) > 1:
            return render_template_string(error_page)

        hair_type = request.form['hair'].lower()
        if '{' in hair_type and re.search(regex,hair_type):
            return render_template_string(error_page)

        if len(hair_type) > 256:
            return render_template_string(error_page)

        page = \
            '''
        {{% extends "layout.html" %}}
        {{% block body %}}
        <center>
           <section class="section">
              <div class="container">
                 <h1 class="title">Dậy đi ông cháu ơi, cắt xong rồi nhé!</h1>
                 <ul class=flashes>
                    <label>Ông cháu có quả đầu {} thanh toán tiền cho chú nào <3</label>
                 </ul>
                 </br>
              </div>
           </section>
           <iframe width="560" height="315" src="https://v16m-webapp.tiktokcdn-us.com/2f678d478e2de26a048aaf4f3ed6d8bd/62b6f7f3/video/tos/useast2a/tos-useast2a-pve-0037-aiso/dd6e434a38e4447e83f61a684c31583b/?a=1988&ch=0&cr=0&dr=0&lr=tiktok&cd=0%7C0%7C0%7C0&br=1302&bt=651&cs=0&ds=1&ft=ebtHKHk_Myq8Z4IeUwe2NsE~fl7Gb&mime_type=video_mp4&qs=0&rc=ZThoZWk7Zzw3PGQ1NmVnM0BpM3VsZWg6ZjhzZDMzZjgzM0AzLjIyYC8tX2AxYGFhMjVhYSNnMS9kcjQwMC1gLS1kL2Nzcw%3D%3D&l=202206250556040100040040250040050060030180F0D3C2C" frameborder="0" allowfullscreen></iframe>
      </iframe>
        </center>
        {{% endblock %}}
        '''.format(hair_type)

    elif request.method == 'GET':
        page = \
            '''
        {% extends "layout.html" %}
        {% block body %}
        <center>
            <section class="section">
              <div class="container">
                 <h1 class="title">Chào mừng đến với <a href="https://www.facebook.com/ehc.fptu">EHC Hair Salon</a>, hôm nay ông cháu này muốn cắt quả đầu nào nhể?</h1>
                 <p>Nhập tên quả đầu mà ông cháu muốn cắt nha!</p>
                 <form action='/' method='POST' align='center'>
                    <p><input name='hair' style='text-align: center;' type='text' placeholder='Tommy Xiaomi' /></p>
                    <p><input value='Submit' style='text-align: center;' type='submit' /></p>
                 </form>
              </div>
           </section>
        </center>
        {% endblock %}
        '''
    return render_template_string(page)


app.run('0.0.0.0', 8000)

