<?php

echo "
   <div id='footer'>
        <div class='py-3' id='blank'></div>
        <div class='row' id='content'>
            <div class='col-md-4' id='left'>
                <a href='{$loc}onlinecoaching/onlinecoaching.html'>Online Coaching</a><br><br>
                <a href='{$loc}personaltraining/personaltraining.html'>Personal Training</a>
            </div>
            <div class='col-md-4' id='middle'>
                <a href='{$loc}about/about.html'>Über Mich</a><br><br>
                <a href='{$loc}imprint/imprint.html'>Impressum & Datenschutz</a>
            </div>
            <div class='col-md-4' id='right'>
                <div id='contact-button'>
                    <a id='contact' href='{$loc}contact/contact.html'>Kontakt</a>
                </div>
                <div id='symbols'>
                    <div>
                        <a href='{$loc}contact/contact.html'>
                            <i class='material-icons' style='font-size:34px'>mail_outline</i>
                        </a>
                    </div>
                    <div>
                        <a href='https://www.instagram.com/'>
                            <i class='fa fa-instagram' style='font-size:30px'></i>
                        </a>
                    </div>
                    <div>
                        <select name='language' id='language'>
                            <option value='de'>DE</option>
                            <option value='en'>EN</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class='py-3' id='copyright'>
            <p >&copy Younes & Stursa</p>
        </div>
    </div>
    ";
