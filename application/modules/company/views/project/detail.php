<link rel="stylesheet" href="<?php echo base_url('backend_assets/css/chat.css')?>"/>

<?php $backend_assets=base_url().'backend_assets/'; ?>
<style>
    .float{
    	position:fixed;
    	width:60px;
    	height:60px;
    	bottom:40px;
    	right:40px;
    	background-color:#0C9;
    	color:#FFF;
    	border-radius:50px;
    	text-align:center;
    	box-shadow: 2px 2px 3px #999;
    }
    .boxed {
        border: 2px solid #71393938;
        border-radius: 22px;
        padding: 72px;
        text-align: center;
        font-size: 15px;
        cursor:pointer;
        background: #245f75;
    color: white;
    }
    
    
#frame {
  width: 95%;
  min-width: 360px;
  max-width: 1000px;
  height: 92vh;
  min-height: 300px;
  max-height: 720px;
  background: #E6EAEA;
}
@media screen and (max-width: 360px) {
  #frame {
    width: 100%;
    height: 100vh;
  }
}
#frame #sidepanel {
  float: left;
  min-width: 280px;
  max-width: 340px;
  width: 40%;
  height: 100%;
  background: #2c3e50;
  color: #f5f5f5;
  overflow: hidden;
  position: relative;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel {
    width: 58px;
    min-width: 58px;
  }
}
#frame #sidepanel #profile {
  width: 80%;
  margin: 25px auto;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile {
    width: 100%;
    margin: 0 auto;
    padding: 5px 0 0 0;
    background: #32465a;
  }
}
#frame #sidepanel #profile.expanded .wrap {
  height: 210px;
  line-height: initial;
}
#frame #sidepanel #profile.expanded .wrap p {
  margin-top: 20px;
}
#frame #sidepanel #profile.expanded .wrap i.expand-button {
  -moz-transform: scaleY(-1);
  -o-transform: scaleY(-1);
  -webkit-transform: scaleY(-1);
  transform: scaleY(-1);
  filter: FlipH;
  -ms-filter: "FlipH";
}
#frame #sidepanel #profile .wrap {
  height: 60px;
  line-height: 60px;
  overflow: hidden;
  -moz-transition: 0.3s height ease;
  -o-transition: 0.3s height ease;
  -webkit-transition: 0.3s height ease;
  transition: 0.3s height ease;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap {
    height: 55px;
  }
}
#frame #sidepanel #profile .wrap img {
  width: 50px;
  border-radius: 50%;
  padding: 3px;
  border: 2px solid #e74c3c;
  height: auto;
  float: left;
  cursor: pointer;
  -moz-transition: 0.3s border ease;
  -o-transition: 0.3s border ease;
  -webkit-transition: 0.3s border ease;
  transition: 0.3s border ease;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap img {
    width: 40px;
    margin-left: 4px;
  }
}
#frame #sidepanel #profile .wrap img.online {
  border: 2px solid #2ecc71;
}
#frame #sidepanel #profile .wrap img.away {
  border: 2px solid #f1c40f;
}
#frame #sidepanel #profile .wrap img.busy {
  border: 2px solid #e74c3c;
}
#frame #sidepanel #profile .wrap img.offline {
  border: 2px solid #95a5a6;
}
#frame #sidepanel #profile .wrap p {
  float: left;
  margin-left: 15px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap p {
    display: none;
  }
}
#frame #sidepanel #profile .wrap i.expand-button {
  float: right;
  margin-top: 23px;
  font-size: 0.8em;
  cursor: pointer;
  color: #435f7a;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap i.expand-button {
    display: none;
  }
}
#frame #sidepanel #profile .wrap #status-options {
  position: absolute;
  opacity: 0;
  visibility: hidden;
  width: 150px;
  margin: 70px 0 0 0;
  border-radius: 6px;
  z-index: 99;
  line-height: initial;
  background: #435f7a;
  -moz-transition: 0.3s all ease;
  -o-transition: 0.3s all ease;
  -webkit-transition: 0.3s all ease;
  transition: 0.3s all ease;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options {
    width: 58px;
    margin-top: 57px;
  }
}
#frame #sidepanel #profile .wrap #status-options.active {
  opacity: 1;
  visibility: visible;
  margin: 75px 0 0 0;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options.active {
    margin-top: 62px;
  }
}
#frame #sidepanel #profile .wrap #status-options:before {
  content: '';
  position: absolute;
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-bottom: 8px solid #435f7a;
  margin: -8px 0 0 24px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options:before {
    margin-left: 23px;
  }
}
#frame #sidepanel #profile .wrap #status-options ul {
  overflow: hidden;
  border-radius: 6px;
}
#frame #sidepanel #profile .wrap #status-options ul li {
  padding: 15px 0 30px 18px;
  display: block;
  cursor: pointer;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options ul li {
    padding: 15px 0 35px 22px;
  }
}
#frame #sidepanel #profile .wrap #status-options ul li:hover {
  background: #496886;
}
#frame #sidepanel #profile .wrap #status-options ul li span.status-circle {
  position: absolute;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin: 5px 0 0 0;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options ul li span.status-circle {
    width: 14px;
    height: 14px;
  }
}
#frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
  content: '';
  position: absolute;
  width: 14px;
  height: 14px;
  margin: -3px 0 0 -3px;
  background: transparent;
  border-radius: 50%;
  z-index: 0;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
    height: 18px;
    width: 18px;
  }
}
#frame #sidepanel #profile .wrap #status-options ul li p {
  padding-left: 12px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options ul li p {
    display: none;
  }
}
#frame #sidepanel #profile .wrap #status-options ul li#status-online span.status-circle {
  background: #2ecc71;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-online.active span.status-circle:before {
  border: 1px solid #2ecc71;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-away span.status-circle {
  background: #f1c40f;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-away.active span.status-circle:before {
  border: 1px solid #f1c40f;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-busy span.status-circle {
  background: #e74c3c;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-busy.active span.status-circle:before {
  border: 1px solid #e74c3c;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-offline span.status-circle {
  background: #95a5a6;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-offline.active span.status-circle:before {
  border: 1px solid #95a5a6;
}
#frame #sidepanel #profile .wrap #expanded {
  padding: 100px 0 0 0;
  display: block;
  line-height: initial !important;
}
#frame #sidepanel #profile .wrap #expanded label {
  float: left;
  clear: both;
  margin: 0 8px 5px 0;
  padding: 5px 0;
}
#frame #sidepanel #profile .wrap #expanded input {
  border: none;
  margin-bottom: 6px;
  background: #32465a;
  border-radius: 3px;
  color: #f5f5f5;
  padding: 7px;
  width: calc(100% - 43px);
}
#frame #sidepanel #profile .wrap #expanded input:focus {
  outline: none;
  background: #435f7a;
}
#frame #sidepanel #search {
  border-top: 1px solid #32465a;
  border-bottom: 1px solid #32465a;
  font-weight: 300;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #search {
    display: none;
  }
}
#frame #sidepanel #search label {
  position: absolute;
  margin: 10px 0 0 20px;
}
#frame #sidepanel #search input {
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
  padding: 10px 0 10px 46px;
  width: calc(100% - 25px);
  border: none;
  background: #32465a;
  color: #f5f5f5;
}
#frame #sidepanel #search input:focus {
  outline: none;
  background: #435f7a;
}
#frame #sidepanel #search input::-webkit-input-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #search input::-moz-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #search input:-ms-input-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #search input:-moz-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #contacts {
  height: calc(100% - 177px);
  overflow-y: scroll;
  overflow-x: hidden;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts {
    height: calc(100% - 149px);
    overflow-y: scroll;
    overflow-x: hidden;
  }
  #frame #sidepanel #contacts::-webkit-scrollbar {
    display: none;
  }
}
#frame #sidepanel #contacts.expanded {
  height: calc(100% - 334px);
}
#frame #sidepanel #contacts::-webkit-scrollbar {
  width: 8px;
  background: #2c3e50;
}
#frame #sidepanel #contacts::-webkit-scrollbar-thumb {
  background-color: #243140;
}
#frame #sidepanel #contacts ul li.contact {
  position: relative;
  padding: 10px 0 15px 0;
  font-size: 0.9em;
  cursor: pointer;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact {
    padding: 6px 0 46px 8px;
  }
}
#frame #sidepanel #contacts ul li.contact:hover {
  background: #32465a;
}
#frame #sidepanel #contacts ul li.contact.active {
  background: #32465a;
  border-right: 5px solid #435f7a;
}
#frame #sidepanel #contacts ul li.contact.active span.contact-status {
  border: 2px solid #32465a !important;
}
#frame #sidepanel #contacts ul li.contact .wrap {
  width: 88%;
  margin: 0 auto;
  position: relative;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact .wrap {
    width: 100%;
  }
}
#frame #sidepanel #contacts ul li.contact .wrap span {
  position: absolute;
  left: 0;
  margin: -2px 0 0 -2px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: 2px solid #2c3e50;
  background: #95a5a6;
}
#frame #sidepanel #contacts ul li.contact .wrap span.online {
  background: #2ecc71;
}
#frame #sidepanel #contacts ul li.contact .wrap span.away {
  background: #f1c40f;
}
#frame #sidepanel #contacts ul li.contact .wrap span.busy {
  background: #e74c3c;
}
#frame #sidepanel #contacts ul li.contact .wrap img {
  width: 40px;
  border-radius: 50%;
  float: left;
  margin-right: 10px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact .wrap img {
    margin-right: 0px;
  }
}
#frame #sidepanel #contacts ul li.contact .wrap .meta {
  padding: 5px 0 0 0;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact .wrap .meta {
    display: none;
  }
}
#frame #sidepanel #contacts ul li.contact .wrap .meta .name {
  font-weight: 600;
}
#frame #sidepanel #contacts ul li.contact .wrap .meta .preview {
  margin: 5px 0 0 0;
  padding: 0 0 1px;
  font-weight: 400;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  -moz-transition: 1s all ease;
  -o-transition: 1s all ease;
  -webkit-transition: 1s all ease;
  transition: 1s all ease;
}
#frame #sidepanel #contacts ul li.contact .wrap .meta .preview span {
  position: initial;
  border-radius: initial;
  background: none;
  border: none;
  padding: 0 2px 0 0;
  margin: 0 0 0 1px;
  opacity: .5;
}
#frame #sidepanel #bottom-bar {
  position: absolute;
  width: 100%;
  bottom: 0;
}
#frame #sidepanel #bottom-bar button {
  float: left;
  border: none;
  width: 50%;
  padding: 10px 0;
  background: #32465a;
  color: #f5f5f5;
  cursor: pointer;
  font-size: 0.85em;
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button {
    float: none;
    width: 100%;
    padding: 15px 0;
  }
}
#frame #sidepanel #bottom-bar button:focus {
  outline: none;
}
#frame #sidepanel #bottom-bar button:nth-child(1) {
  border-right: 1px solid #2c3e50;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button:nth-child(1) {
    border-right: none;
    border-bottom: 1px solid #2c3e50;
  }
}
#frame #sidepanel #bottom-bar button:hover {
  background: #435f7a;
}
#frame #sidepanel #bottom-bar button i {
  margin-right: 3px;
  font-size: 1em;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button i {
    font-size: 1.3em;
  }
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button span {
    display: none;
  }
}
#frame .content {
  float: right;
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
}
#frame .content .contact-profile {
  width: 100%;
  height: 60px;
  line-height: 60px;
  background: #f5f5f5;
}
#frame .content .contact-profile img {
  width: 40px;
  border-radius: 50%;
  float: left;
  margin: 9px 12px 0 9px;
}
#frame .content .contact-profile p {
  float: left;
}
#frame .content .contact-profile .social-media {
  float: right;
}
#frame .content .contact-profile .social-media i {
  margin-left: 14px;
  cursor: pointer;
}
#frame .content .contact-profile .social-media i:nth-last-child(1) {
  margin-right: 20px;
}
#frame .content .contact-profile .social-media i:hover {
  color: #435f7a;
}
#frame .content .messages {
  height: auto;
  min-height: calc(100% - 93px);
  max-height: calc(100% - 93px);
  overflow-y: scroll;
  overflow-x: hidden;
}
@media screen and (max-width: 735px) {
  #frame .content .messages {
    max-height: calc(100% - 105px);
  }
}
#frame .content .messages::-webkit-scrollbar {
  width: 8px;
  background: transparent;
}
#frame .content .messages::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.3);
}
#frame .content .messages ul li {
  display: inline-block;
  clear: both;
  float: left;
  margin: 15px 15px 5px 15px;
  width: calc(100% - 25px);
  font-size: 0.9em;
}
#frame .content .messages ul li:nth-last-child(1) {
  margin-bottom: 20px;
}
#frame .content .messages ul li.sent img {
  margin: 6px 8px 0 0;
}
#frame .content .messages ul li.sent p {
  background: #435f7a;
  color: #f5f5f5;
}
#frame .content .messages ul li.replies img {
  float: right;
  margin: 6px 0 0 8px;
}
#frame .content .messages ul li.replies p {
  background: #f5f5f5;
  float: right;
}
#frame .content .messages ul li img {
  width: 22px;
  border-radius: 50%;
  float: left;
}
#frame .content .messages ul li p {
  display: inline-block;
  padding: 10px 15px;
  border-radius: 20px;
  max-width: 205px;
  line-height: 130%;
}
@media screen and (min-width: 735px) {
  #frame .content .messages ul li p {
    max-width: 300px;
  }
}
#frame .content .message-input {
  position: absolute;
  bottom: 0;
  width: 100%;
  z-index: 99;
}
#frame .content .message-input .wrap {
  position: relative;
}
#frame .content .message-input .wrap input {
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
  float: left;
  border: none;
  width: calc(100% - 90px);
  padding: 11px 32px 10px 8px;
  font-size: 0.8em;
  color: #32465a;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap input {
    padding: 15px 32px 16px 8px;
  }
}
#frame .content .message-input .wrap input:focus {
  outline: none;
}
#frame .content .message-input .wrap .attachment {
  position: absolute;
  right: 60px;
  z-index: 4;
  margin-top: 10px;
  font-size: 1.1em;
  color: #435f7a;
  opacity: .5;
  cursor: pointer;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap .attachment {
    margin-top: 17px;
    right: 65px;
  }
}
#frame .content .message-input .wrap .attachment:hover {
  opacity: 1;
}
#frame .content .message-input .wrap button {
  float: right;
  border: none;
  width: 50px;
  padding: 12px 0;
  cursor: pointer;
  background: #32465a;
  color: #f5f5f5;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap button {
    padding: 16px 0;
  }
}
#frame .content .message-input .wrap button:hover {
  background: #435f7a;
}
#frame .content .message-input .wrap button:focus {
  outline: none;
}

.card {
  position: relative;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}

.card > hr {
  margin-right: 0;
  margin-left: 0;
}

.card > .list-group {
  border-top: inherit;
  border-bottom: inherit;
}

.card > .list-group:first-child {
  border-top-width: 0;
  border-top-left-radius: calc(0.25rem - 1px);
  border-top-right-radius: calc(0.25rem - 1px);
}

.card > .list-group:last-child {
  border-bottom-width: 0;
  border-bottom-right-radius: calc(0.25rem - 1px);
  border-bottom-left-radius: calc(0.25rem - 1px);
}

.card-body {
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  min-height: 1px;
  padding: 1.25rem;
}

.card-title {
  margin-bottom: 0.75rem;
}

.card-subtitle {
  margin-top: -0.375rem;
  margin-bottom: 0;
}

.card-text:last-child {
  margin-bottom: 0;
}

.card-link:hover {
  text-decoration: none;
}

.card-link + .card-link {
  margin-left: 1.25rem;
}

.card-header {
  padding: 0.75rem 1.25rem;
  margin-bottom: 0;
  background-color: rgba(0, 0, 0, 0.03);
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header:first-child {
  border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}

.card-header + .list-group .list-group-item:first-child {
  border-top: 0;
}

.card-footer {
  padding: 0.75rem 1.25rem;
  background-color: rgba(0, 0, 0, 0.03);
  border-top: 1px solid rgba(0, 0, 0, 0.125);
}

.card-footer:last-child {
  border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
}

.card-header-tabs {
  margin-right: -0.625rem;
  margin-bottom: -0.75rem;
  margin-left: -0.625rem;
  border-bottom: 0;
}

.card-header-pills {
  margin-right: -0.625rem;
  margin-left: -0.625rem;
}

.card-img-overlay {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1.25rem;
}

.card-img,
.card-img-top,
.card-img-bottom {
  -ms-flex-negative: 0;
  flex-shrink: 0;
  width: 100%;
}

.card-img,
.card-img-top {
  border-top-left-radius: calc(0.25rem - 1px);
  border-top-right-radius: calc(0.25rem - 1px);
}

.card-img,
.card-img-bottom {
  border-bottom-right-radius: calc(0.25rem - 1px);
  border-bottom-left-radius: calc(0.25rem - 1px);
}

.card-deck .card {
  margin-bottom: 15px;
}

@media (min-width: 576px) {
  .card-deck {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
    margin-right: -15px;
    margin-left: -15px;
  }
  .card-deck .card {
    -ms-flex: 1 0 0%;
    flex: 1 0 0%;
    margin-right: 15px;
    margin-bottom: 0;
    margin-left: 15px;
  }
}

.card-group > .card {
  margin-bottom: 15px;
}

@media (min-width: 576px) {
  .card-group {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
  }
  .card-group > .card {
    -ms-flex: 1 0 0%;
    flex: 1 0 0%;
    margin-bottom: 0;
  }
  .card-group > .card + .card {
    margin-left: 0;
    border-left: 0;
  }
  .card-group > .card:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }
  .card-group > .card:not(:last-child) .card-img-top,
  .card-group > .card:not(:last-child) .card-header {
    border-top-right-radius: 0;
  }
  .card-group > .card:not(:last-child) .card-img-bottom,
  .card-group > .card:not(:last-child) .card-footer {
    border-bottom-right-radius: 0;
  }
  .card-group > .card:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
  .card-group > .card:not(:first-child) .card-img-top,
  .card-group > .card:not(:first-child) .card-header {
    border-top-left-radius: 0;
  }
  .card-group > .card:not(:first-child) .card-img-bottom,
  .card-group > .card:not(:first-child) .card-footer {
    border-bottom-left-radius: 0;
  }
}

.card-columns .card {
  margin-bottom: 0.75rem;
}

@media (min-width: 576px) {
  .card-columns {
    -webkit-column-count: 3;
    -moz-column-count: 3;
    column-count: 3;
    -webkit-column-gap: 1.25rem;
    -moz-column-gap: 1.25rem;
    column-gap: 1.25rem;
    orphans: 1;
    widows: 1;
  }
  .card-columns .card {
    display: inline-block;
    width: 100%;
  }
}

.accordion > .card {
  overflow: hidden;
}

.accordion > .card:not(:last-of-type) {
  border-bottom: 0;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.accordion > .card:not(:first-of-type) {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.accordion > .card > .card-header {
  border-radius: 0;
  margin-bottom: -1px;
}

.input-icons i { 
            position: absolute; 
        } 
          
        .input-icons { 
            width: 100%; 
            margin-bottom: 10px; 
        } 
          
        .icon { 
            padding: 14px; 
            min-width: 40px; 
        } 
          
        .input-field { 
            width: 100%; 
            padding: 10px; 
            border-radius: 10px;
            padding-left: 34px;
            border: 1px solid;
            //text-align: center; 
        }
        
        
        /* panel layout mechanics */
.panel-wrap {
  position: fixed;
  top: 0;
  bottom: 0;
  right: 0;
  width: 30em;
  transform: translateX(100%);
  transition: .3s ease-out;
  z-index:9999;
}
.panel {
    text-align:center;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: white;
  color: black;
  overflow: auto;
  padding: 1em;
}

/* simulate panel state control --
this can also be triggered by a 
class name added to the body tag. 
Just using a checkbox sibling here
for simplicity
*/ 
[type="checkbox"]:checked ~ .panel-wrap {
  transform: translateX(0%);
}


/* demo display */
*,*:before, *:after {box-sizing: border-box;}


#square {
  position: relative;
	width: 800px;
	height: 450px;
  display: block;
  margin: auto;
	background: white;
  z-index: 1;
}
	
#leftSquare {
	width: 26%;
	height: 100%;
  position: relative;
  top: 0%;
  left: 0%;
	background:#4D394B;
  background-size: cover;
  border-radius: 0%;
  z-index: 1;
}

#highlight-channel{
  position: absolute;
  top: 24%;
  width: 90%;
  border-top-right-radius: 1%;
   border-bottom-right-radius: 1%;
  border: 10px solid #4C9689;
}

/*#header {*/
/*	width: 75%;*/
/*  display: block;*/
/*  margin:  auto;*/
/*	height: 15%;*/
/*  position: absolute;*/
/*  top: 0%;*/
/*  left: 25%;*/
/*	background:none;*/
/*  background-size: cover;*/
/*  border-radius: 0%;*/
/*  z-index: 1;*/
/*}*/

#circle{
    position: absolute;
    border-radius: 50%;
    left: 13%;
    top: 11%;
    width: 4%;
    height: 2%;
    display: block;
    background: #4C9689;
    margin: auto;
    z-index: 3;
  }

#circle2{
    position: absolute;
    border-radius: 50%;
    left: 16%;
    top: 60%;
    width: 4%;
    height: 2%;
    display: block;
    background: #4C9689;
    margin: auto;
    z-index: 3;
  }
#circle3{
    position: absolute;
    border-radius: 50%;
    left: 16%;
    top: 65%;
    width: 4%;
    height: 2%;
    display: block;
    background: #4C9689;
    margin: auto;
    z-index: 3;
  }

.brand{
  position: absolute;
  display: block;
  top: 38%;
  left: 25%;
  margin: auto;
  width: 50%;
  height: 60%;
  z-index: 1;
  
}

#icons{
  
  padding-bottom: 5px;
  
}

.title{
  position: absolute;
  /*top: 5%;*/
  color: white;
  font-family:"lato";
  font-size: 20px;
  font-weight: 800;
  padding-left: 13%;
  text-align: left;
  z-index: 3;
}
#subtitle{
  position: absolute;
  left: 19%;
  top: 6%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.5;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}
#subtitle2{
  position: absolute;
  left: 22%;
  top: 54.7%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.5;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}
#subtitle3{
  position: absolute;
  left: 22%;
  top: 59.7%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.5;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}

#channel-title{
  color: #2C2D30;
  font-family:"lato";
  font-size: 18px;
  font-weight: 800;
  padding-left: 3%;
  text-align: left;
  z-index: 3;
}
#channels{
  position: absolute;
  left: 13%;
  top: 14%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.6;
  font-weight: 400;
  text-align: center;
  z-index: 3;
}

.add{
  position: absolute;
  right: -72%;
  opacity: 0.6;
  top: 5%;
  
}

.add-two{
  position: absolute;
  right: -15%;
  opacity: 0.6;
  top: 5%;
  z-index: 3;
  
}

.star{
  padding-left: 3%;
  opacity: 0.6;
  z-index: 3;
  
}
.user{
  padding-left: 4%;
  opacity: 0.6;
  z-index: 3;
  
}
.pin{
  padding-left: 4%;
  opacity: 0.4;
  z-index: 3;
  
}
.phone{
  position: absolute;
  top: 45%;
  right: 48%;
  opacity: 0.6;
  z-index: 3;
}
  
.cog{
  position: absolute;
  top: 45%;
  right: 42%;
  opacity: 0.6;
  z-index: 3;
  
}

.details{
  position: absolute;
  top: 45%;
  right: 36%;
  opacity: 0.6;
  z-index: 3;
  
}
#line3{
  
  position: absolute;
  right: 33%;
  top: 8.5%;
  font-family:"lato";
  font-size: 18px;
  opacity: 0.5;
  font-weight: 100;
  color: #2C2D30;
  
}

.search-glass{
   position: absolute;
  top: 43%;
  right: 28%;
  opacity: 0.5;
  z-index: 3;
  
}

.at{
  
  position: absolute;
  top: 45%;
  right: 12%;
  opacity: 0.6;
  z-index: 3;
}

.star-o{
  
   position: absolute;
  top: 45%;
  right: 7%;
  opacity: 0.6;
  z-index: 3;
  
}

.menu{
  
     position: absolute;
  top: 45%;
  right: 3%;
  opacity: 0.6;
  z-index: 3;
}

.search{
  position: absolute;
  right: 17%;
  top: 38%;
  height: 30%;
  width: 14%;
  font-weight: 100;
  padding-left: 20px;
  font-family:"lato";
  font-size: 12px;
  opacity: 0.6;
  color: #2C2D30;
}

#line{
  
  position: absolute;
  left: 7%;
  top: 46.5%;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.5;
  font-weight: 100;
  color: #2C2D30;
  
}
#line2{
  
  position: absolute;
  left: 14%;
  top: 46.5%;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.5;
  font-weight: 100;
  color: #2C2D30;
  
}

.bottom-header-line{
  margin-left: 8px;
  width: 98%;
  margin-top: 0;
  
}


#channel{
  position: absolute;
  left: 16%;
  top: 20%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 1.0;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}
#messages{
  position: absolute;
  left: 13%;
  top: 50%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.6;
  font-weight: 400;
  text-align: center;
  z-index: 3;
}
#channel2{
  position: absolute;
  left: 16%;
  top: 26%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}
#channel3{
  position: absolute;
  left: 16%;
  top: 32%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.6;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}
#channel4{
  position: absolute;
  left: 16%;
  top: 38%;
  color: white;
  font-family:"lato";
  font-size: 13px;
  opacity: 0.6;
  font-weight: 100;
  text-align: center;
  z-index: 3;
}

#highlight{
  
  background: #4C9689;
  
}
#light{
  margin-left: 5px;
  opacity: 0.5;
}

#opacity{
  color: white;
  font-weight: 800;
  opacity: 0.6;
}

#bold{
  color: white;
  font-weight: 800;
  opacity: 1.0;
}


.bell{
  position: absolute;
  color: #D0CDD0;
  opacity: 0.6;
  top: 5%;
  left: 89%;
  
}

.down{
   position: absolute;
  color: #D0CDD0;
  opacity: 0.3;
  top: 4.25%;
  left: 80%;
  
}

#message-area{
  
  position: absolute;
  background: none;
  width: 70%;
  right: 2%;
  top: 18%;
  display: block;
  margin:  auto;
  
}

#message-area-two{
  
  position: absolute;
  background: none;
  width: 70%;
  right: 2%;
  top: 30%;
  display: block;
  margin:  auto;
  
}
#message-area-three{
  
  position: absolute;
  background: none;
  width: 70%;
  right: 2%;
  top: 42%;
  display: block;
  margin:  auto;
  
}
#message-area-four{
  
  position: absolute;
  background: none;
  width: 70%;
  right: 2%;
  top: 54%;
  display: block;
  margin:  auto;
  
}
#message-area-five{
  
  position: absolute;
  background: none;
  width: 70%;
  right: 2%;
  top: 66%;
  display: block;
  margin:  auto;
  
}
#message-area-six{
  
  position: absolute;
  background: none;
  width: 70%;
  right: 2%;
  top: 78%;
  display: block;
  margin:  auto;
  
}
.person-one{
  width: 50px;
  height: 50px;
  border: white;
}

.person-img-one{
    border-radius: 10%;
}

#person-name{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  font-weight: 800;
  padding-left: 10%;
  top: -5%;
  text-align: left;
  z-index: 3;
}

#time{
  color: #2C2D30;
  padding-left: 5px;
  font-family:"lato";
  font-size: 11px;
  opacity: 0.6;
  font-weight: 400;
}

#person-text{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  margin-bottom:0;
  font-weight: 400;
  opacity: 0.7;
  padding-left: 10%;
  top: 25%;
  text-align: left;
  z-index: 3;
}

.person-two{
  width: 50px;
  height: 50px;
  border: white;
}

.person-img-two{
    border-radius: 10%;
}

#person-name-two{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  font-weight: 800;
  padding-left: 10%;
  top: -5%;
  text-align: left;
  z-index: 3;
}

#person-text-two{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  margin-bottom:0;
  font-weight: 400;
  opacity: 0.7;
  padding-left: 10%;
  top: 25%;
  text-align: left;
  z-index: 3;
}

.person-three{
  width: 50px;
  height: 50px;
  border: white;
}

.person-img-three{
    border-radius: 10%;
}

#person-name-three{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  font-weight: 800;
  padding-left: 10%;
  top: -5%;
  text-align: left;
  z-index: 3;
}
#person-text-three{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  margin-bottom:0;
  font-weight: 400;
  opacity: 0.7;
  padding-left: 10%;
  top: 25%;
  text-align: left;
  z-index: 3;
}

.person-four{
  width: 50px;
  height: 50px;
  border: white;
}

.person-img-four{
    border-radius: 10%;
}

#person-name-four{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  font-weight: 800;
  padding-left: 10%;
  top: -5%;
  text-align: left;
  z-index: 3;
}
#person-text-four{ 
  position: absolute;
  color: #2C2D30;
  font-family:"lato";
  font-size: 15px;
  margin-bottom:0;
  font-weight: 400;
  opacity: 0.7;
  padding-left: 10%;
  top: 25%;
  text-align: left;
  z-index: 3;
}


.search-bottom{
  position: absolute;
  display: block;
  margin: auto;
  left: 28%;
  bottom: 3%;
  height: 5%;
  width: 70%;
  font-weight: 100;
  padding-left: 28px;
  font-family:"lato";
  font-size: 12px;
  opacity: 0.6;
  color: #2C2D30;
}

.smile{
  
  position: absolute;
  display: block;
  margin: auto;
  bottom: 4%;
  right: 3%;
  opacity: 0.6;
  color: #2C2D30;
}

.plus{
  
  position: absolute;
  display: block;
  margin: auto;
  bottom: 3.75%;
  left: 28.75%;
  opacity: 0.3;
  color: #2C2D30;
}

#line-bottom{
   position: absolute;
  display: block;
  margin: auto;
  bottom: 3.1%;
  left: 30.75%;
  opacity: 0.1;
  font-family:"lato";
  font-size: 22px;
  opacity: 0.3;
  font-weight: 100;
  color: #2C2D30;
}

* {
  box-sizing: border-box;
}

body {
  background-color: #edeff2;
  font-family: "Calibri", "Roboto", sans-serif;
}

.chat_window {
  position: absolute;
  width: calc(100% - 20px);
  max-width: 800px;
  height: 500px;
  border-radius: 10px;
  background-color: #fff;
  left: 50%;
  top: 50%;
  transform: translateX(-50%) translateY(-50%);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  background-color: #f8f8f8;
  overflow: hidden;
  margin-top: 43px;
}

.top_menu {
  background-color: #fff;
  width: 100%;
  padding: 20px 0 15px;
  box-shadow: 0 1px 30px rgba(0, 0, 0, 0.1);
  height:43px;
}
.top_menu .buttons {
  margin: 3px 0 0 20px;
  position: absolute;
}
.top_menu .buttons .button {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  display: inline-block;
  margin-right: 10px;
  position: relative;
}
.top_menu .buttons .button.close {
  background-color: #f5886e;
}
.top_menu .buttons .button.minimize {
  background-color: #fdbf68;
}
.top_menu .buttons .button.maximize {
  background-color: #a3d063;
}
.top_menu .title {
  text-align: center;
  color: #bcbdc0;
  font-size: 20px;
}

.messages {
  position: relative;
  list-style: none;
  padding: 20px 10px 0 10px;
  margin: 0;
  height: 347px;
  overflow: scroll;
}
.messages .message {
  clear: both;
  overflow: hidden;
  margin-bottom: 20px;
  transition: all 0.5s linear;
  opacity: 0;
}
.messages .message.left .avatar {
  background-color: #f5886e;
  float: left;
}
.messages .message.left .text_wrapper {
  background-color: #ffe6cb;
  margin-left: 20px;
}
.messages .message.left .text_wrapper::after, .messages .message.left .text_wrapper::before {
  right: 100%;
  border-right-color: #ffe6cb;
}
.messages .message.left .text {
  color: #c48843;
}
.messages .message.right .avatar {
  background-color: #fdbf68;
  float: right;
}
.messages .message.right .text_wrapper {
  background-color: #c7eafc;
  margin-right: 20px;
  float: right;
}
.messages .message.right .text_wrapper::after, .messages .message.right .text_wrapper::before {
  left: 100%;
  border-left-color: #c7eafc;
}
.messages .message.right .text {
  color: #45829b;
}
.messages .message.appeared {
  opacity: 1;
}
.messages .message .avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: inline-block;
}
.messages .message .text_wrapper {
  display: inline-block;
  padding: 20px;
  border-radius: 6px;
  width: calc(100% - 85px);
  min-width: 100px;
  position: relative;
}
.messages .message .text_wrapper::after, .messages .message .text_wrapper:before {
  top: 18px;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}
.messages .message .text_wrapper::after {
  border-width: 13px;
  margin-top: 0px;
}
.messages .message .text_wrapper::before {
  border-width: 15px;
  margin-top: -2px;
}
.messages .message .text_wrapper .text {
  font-size: 18px;
  font-weight: 300;
}

.bottom_wrapper {
  position: relative;
  width: 100%;
  background-color: #fff;
  padding: 20px 20px;
  position: absolute;
  bottom: 0;
}
.bottom_wrapper .message_input_wrapper {
  display: inline-block;
  height: 50px;
  border-radius: 25px;
  border: 1px solid #bcbdc0;
  width: calc(100% - 160px);
  position: relative;
  padding: 0 20px;
}
.bottom_wrapper .message_input_wrapper .message_input {
  border: none;
  height: 100%;
  box-sizing: border-box;
  width: calc(100% - 40px);
  position: absolute;
  outline-width: 0;
  color: gray;
}
.bottom_wrapper .send_message {
  width: 140px;
  height: 50px;
  display: inline-block;
  border-radius: 50px;
  background-color: #a3d063;
  border: 2px solid #a3d063;
  color: #fff;
  cursor: pointer;
  transition: all 0.2s linear;
  text-align: center;
  float: right;
}
.bottom_wrapper .send_message:hover {
  color: #a3d063;
  background-color: #fff;
}
.bottom_wrapper .send_message .text {
  font-size: 18px;
  font-weight: 300;
  display: inline-block;
  line-height: 48px;
}

.message_template {
  display: none;
}

.incoming_msg_img img{
    height: 35px!important;
    border-radius: 50%!important;
}
.outgoing_msg img{
    height: 35px!important;
    border-radius: 50%!important;
}
</style></

</style>
<?php
    
    if(isset($_GET['type']) && $_GET['type']=="view" )
    {
        $is_show = true;
    }else{
        $is_show = false;
    }

?>

<?php if($is_show){?> 
<div class="row">

	<div class="col-sm-12">
	    
	    <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/project'); ?>">Project</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $project['name']; ?></li>
          </ol>
        </nav>
        
        <style>
            h5{
                margin-top:2px;
                margin-bottom:2px;
                font-size:16px;
            }
        </style>


			<div class="well well-sm">

				<div class="row">

					
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="well well-light well-sm no-margin padding-4">

							<div class="row">
								<div class="col-sm-12">

									<div class="row">
										<div class="col-sm-12 padding-left-1">
											<h3 class="margin-top-0">Project Name  : <a href="javascript:void(0);"> <?= $project['name']; ?> </a></h3>

                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <h5 style="font-weight: 500;">Description</h5>
                                                </div>
                                                <div class="col-sm-1">
                                                    <h5 style="font-weight: 500;" class="text-center"> : </h5>
                                                </div>
                                                <div class="col-sm-9">
                                                    <h5 style="font-weight: 500;"><?= $project['project_description']; ?></h5>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <h5 style="font-weight: 500;">Status</h5>
                                                </div>
                                                <div class="col-sm-1">
                                                    <h5 style="font-weight: 500;" class="text-center"> : </h5>
                                                </div>
                                                <div class="col-sm-9">
                                                    <h5 style="font-weight: 500;">
                                                        <?php
                                                            if($project['status']==0)
            											    {
            											        echo 'In-progress';
            											    }else{
            											        echo 'Completed';
            											    }
        											    ?>
                                                    </h5>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <h5 style="font-weight: 500;">Start Date</h5>
                                                </div>
                                                <div class="col-sm-1">
                                                    <h5 style="font-weight: 500;" class="text-center"> : </h5>
                                                </div>
                                                <div class="col-sm-9">
                                                    <h5 style="font-weight: 500;">
                                                        <?php echo date("M d, Y",strtotime($project['start_date'])); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <h5 style="font-weight: 500;">Estimated End Date</h5>
                                                </div>
                                                <div class="col-sm-1">
                                                    <h5 style="font-weight: 500;" class="text-center"> : </h5>
                                                </div>
                                                <div class="col-sm-9">
                                                    <h5 style="font-weight: 500;">
                                                        <?php echo date("M d, Y",strtotime($project['end_date'])); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            <?php
    										    
    										        if($client_data != "")
    										        {
    										        ?>
    										        
    										            <div class="row">
                                                            <div class="col-sm-2">
                                                                <h5 style="font-weight: 500;">Client Name</h5>
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <h5 style="font-weight: 500;" class="text-center"> : </h5>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <h5 style="font-weight: 500;">
                                                                    <?php echo $client_data->name; ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <h5 style="font-weight: 500;">Client Email</h5>
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <h5 style="font-weight: 500;" class="text-center"> : </h5>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <h5 style="font-weight: 500;">
                                                                    <?php echo $client_data->email; ?>
                                                                </h5>
                                                            </div>
                                                        </div>
    										            
    										        <?php }
    										    
    										    ?>
                                        
											
											
											<h5 style="font-weight: 500;">Project Document</h5>
											
											
    										<?php
    										
    										    if($project['document'] == "" || $project['document'] == NULL)
    										    {
    										        
    										    }else{
        										    $mediapath = base_url('uploads/project/').$project['document'];
    											    if(@is_array(getimagesize($mediapath))){
                                                        $image = 1;
                                                    } else {
                                                        $image = 0;
                                                    }
        										
        										    if($image == 0)
        										    { ?>
                                                        <div>
                                                            <object style="width:100%; overflow:hidden;" src="<?php echo base_url('uploads/client/').$project['document']; ?>"><iframe style="width:450px;height:400px;" src="https://docs.google.com/viewer?url=<?php echo base_url('uploads/client/').$project['document']; ?>&embedded=true"></iframe></object>
                                                        </div>
        										    
        										    <?php }else{ ?>
        										        <div>
                                                            <img style="height:300px;" src="<?php echo $mediapath; ?>"/>
                                                        </div>
        										    
        										    <?php } 
    										    } ?>
    										    
    										    
    										    
											

                                        
                                                
									</div>
								</div>
							</div>
						</div>
					</div>
																		
																																										
																						
									</div>		
								</div>

							</div>
					</div>
				</div>
			</div>
	</div>
</div>

<?php } ?>

<input type="hidden" id="hiddenId" value="<?php echo decoding(end($this->uri->segment_array())); ?>" />
<?php if(!$is_show){?>
<div class="row">
	<div class="col-sm-12">
		<div class="well well-sm">
			<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="well well-light well-sm no-margin padding-4">
						    <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                              <li class="active"><a href="#task" role="tab" data-toggle="tab">Task</a></li>
                              <li><a href="#people" role="tab" data-toggle="tab">People</a></li>
                              <li><a href="#wall" role="tab" data-toggle="tab">Wall</a></li>
                              <li><a href="#document" role="tab" data-toggle="tab">Documents</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                              <div class="tab-pane active" id="task">
                                  <!-- form --->
                                  <br>
                                  <div class="container text-center">
                             <script> 
                            function myFunction() {
                                $('#task_name').val("");
                                $('#task_status').val(-1);
                                $('#myForm').submit();
                            }                      
                            </script> 
                        
                                  <form id="myForm" class="form-inline" method="post">
                                      
                                      <div class="form-group">
                                          Filter Task : 
                                      </div>
                                      <div class="form-group">
                                        <input type="text" class="form-control" value="<?php if(isset($_POST['task_name']) && $_POST['task_name'] !== "") echo $_POST['task_name']; ?>" id="task_name" name="task_name" placeholder="Enter Task Name">
                                      </div>
                                      <div class="form-group">
                                        <select name="task_status" id="task_status" class="form-control">
                                            <option <?php if(isset($_POST['task_status']) && $_POST['task_status'] == -1) echo 'selected'; ?> value="-1" selected>All</option>
                                            <option <?php if(isset($_POST['task_status']) && $_POST['task_status'] == 0) echo 'selected'; ?> value="0">In Progress</option>
                                            <option <?php if(isset($_POST['task_status']) && $_POST['task_status'] == 1) echo 'selected'; ?> value="1">Completed</option>
                                        </select>
                                      </div>
                                      
                                      <button type="submit" class="btn btn-default" name="filter">Submit</button>
                                      <input type="button" onclick="myFunction()" class="btn btn-default" value="Reset">
                                    </form>
                    
                                    
                                    </div>
                                    
                                    <button type="submit" style="color:white;background-color:#245f75;float:right;" class="btn btn-default" data-toggle="modal" data-target="#exampleModal">Add Task <i class="fa fa-plus my-float"></i></button>
                                    
                                    <br><br>
                     <table  class="table table-striped table-bordered table-hover dataTables-example-list" width="100%">
        								<thead>			                
        									<tr>
        										<th data-hide="phone">ID</th>
        										<th data-hide="phone,tablet">Task Name</th>
        										<th data-hide="phone,tablet">Status</th>
        										<th data-hide="phone,tablet">Created at</th>
        										<th data-hide="phone,tablet">Action</th>
        									</tr>
        								</thead>
        								<tbody>		
        								                                      <?php
                                    foreach($task_list as $index=>$value)
                                    { ?>
                                        <tr>
                                            <td><?php echo ($index+1); ?></td>
                                            <td><a  href="<?php echo base_url('admin/task-detail/').encoding($value->taskId); ?>" class="on-default edit-row table_action" title="Detail"><?php echo $value->name; ?></a></td>
                                            <td>
                                            <?php
                                                if($value->task_status == 0)
                                                {
                                                    echo "In Progress";
                                                }else{
                                                    echo "Completed";
                                                }
                                            ?>
                                            </td>
                                            <td><?php echo $value->crd; ?></td>
                                            <td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="javascript:void(0)" onclick="ChangeTaskStatus('<?php echo $value->taskId; ?>');" data-list="1" class="on-default edit-row table_action" title="Status">
                                                    <?php
                                                        if($value->task_status == 0)
                                                        { ?>
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        <?php }else{ ?>
                                                            <i class="fa fa-check" aria-hidden="true"></i>
                                                    <?php }
                                                    ?>
                                                </a>
																							
																													
                                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <a  href="<?php echo base_url('company/tasks/edit/').encoding($value->taskId); ?>" class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <a  href="<?php echo base_url('company/tasks/viewtask/').encoding($value->taskId); ?>?type=view" class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?php echo encoding($value->taskId);?>" data-url="company/Tasksapi/recordDelete" data-list="0"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																								&nbsp;&nbsp;
																								
																								<!-- <a href="javascript:void(0)" onclick="ChangeTaskApprovel('<?php echo $value->taskId; ?>');" data-list="1" class="on-default edit-row table_action" title="Approvel">
                                                    <?php
                                                        if($value->task_approved == 0)
                                                        { ?>
                                                            <i class="glyphicon glyphicon-thumbs-down" aria-hidden="true"> (Not Approved)</i>
                                                        <?php }else{ ?>
                                                            <i class="glyphicon glyphicon-thumbs-up" aria-hidden="true"> (Approved)</i>
                                                    <?php }
                                                    ?>
																								</a> -->
																								
                                            </td>
                                        </tr>   
                                   <?php }
                                  ?>
        								</tbody>
        							</table>
                                  
                                  <!--- end --->
                                  
                                  <!--<button type="button" class="float" data-toggle="modal" data-target="#exampleModal">-->
                                  <!--        <i class="fa fa-plus my-float"></i>-->
                                  <!--</button>-->
                              </div>
                              <div class="tab-pane" id="people">
                                  
                                  <div class="row" style="margin-top: 20px;">
                                      <div class="col-sm-6">
                                            <p style="font-size:20px;">People</p>      
                                      </div>
                                      <div class="col-sm-6 text-right">
                                            <button id="openclosebtn" onclick="openOptionsbtn()" style="background: white;border: 1px solid #cec7c7;float:right;font-size:18px;" class="btn">Invite People</button>
                                            
                                            <button onclick="openExistingModel()" style="background: white;border: 1px solid #cec7c7;font-size:18px;display:none;margin-right:10px;" class="btn optionbtn">Choose Existing</button>
                                            <button onclick="openModel()" style="background: white;border: 1px solid #cec7c7;float:right;font-size:18px;display:none;" class="btn optionbtn">Invite New</button>
                                      </div>
                                      
                                  </div>
                                  
                                  <hr style="height: 1px;
                                    background-color: #e6dede;
                                    border: none;
                                    margin-top: 11px;">
    
                                    <div style="margin:auto"> 
                                        <div class="input-icons"> 
                                            <i class="fa fa-search icon"></i> 
                                            <input onkeyup="searchmembers(this.value)" id="thisissearchfield" class="input-field" placeholder="Seacrh by name or role " type="text"> 
                                        </div> 
                                    </div> 
  
                                  <div id="add_people_button" style="float:right;display:none;">
                                      <button class="btn" onclick="openAddPeopleSection()" style="background: black;color: white;padding: 10px 25px;font-size: 16px;margin-top: 20px;margin-bottom: 15px;float:right;" >+ Add People</button>
                                      
                                      <div id="add_people_section" style="display:none;">
                                          <button onclick="openExistingModel()" class="btn" style="background: #380bea;color: white;padding: 10px 25px;font-size: 16px;margin-top: 20px;margin-right: 6px;" >Choose Existing</button>
                                          
                                          <button onclick="openModel()" class="btn" style="background: #380bea;color: white;padding: 10px 25px;font-size: 16px;margin-top: 20px;margin-left: 6px;" >Invite New</button>
                                      </div>
                                  </div>
                                  <div class="row" id="involved_members">
                                  </div>
                                  <div class="row" id="noninvolved_members">
                                  </div>
                                  
                              </div>
                                <div class="tab-pane" id="wall">
                                     <div class="">
                                         <div class="text-right" style="margin-right:100px;padding-top: 10px;">
                                             <input type="text" id="search_for_task_chat" value="" placeholder="search" />
                                             &nbsp;&nbsp;&nbsp;
                                             <select id="search_for_task_chat_docs">
                                                 <option value="-1">All</option>
                                            <?php
                                                foreach($task_list as $value)
                                                { ?>
                                                <option value="<?php echo $value->taskId; ?>">
                                                    <?php echo $value->name; ?>
                                                </option>
                                                <?php }
                                            ?>
                                             </select>
                                         </div>
                                        <div class="mesgs">
                                          <div class="msg_history" id="yourDivID">
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font">Rahul</label> </div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>Test which is a new approach to have all
                                                    solutions</p>
                                                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
                                                </div>
                                             </div>
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">  <label class="Name_font">vikash</label></div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
                                              </div>
                                            </div>
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font">Rahul</label> </div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
                                              </div>
                                            </div>
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >vikash</label> </div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
                                              </div>
                                            </div>
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">  <label class="Name_font">Rahul</label></div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>We work directly with our designers and suppliers,
                                                    and sell direct to you, which means quality, exclusive
                                                    products, at a price anyone can afford.</p>
                                                  <span class="time_date"> 11:01 AM    |    Today</span></div>
                                              </div>
                                            </div>
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >vikash</label> </div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span>
                                                
                                                </div>
                                              </div>
                                            </div>
                                            <div class="outgoing_msg">
                                            <div style="width:3%"><img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"><label class="Name_font" >Rahul</label></div>
                                             
                                            <div class="custom-file">
                                                <span class="hiddenFileInput">
                                                    <input type="file" name="theFile"/>
                                                </span>
                                                <span class="time_date"> 11:01 AM    |    Today</span>
                                                <label >doc.pdf</label>
                                            </div>
                                            </div>
                                
                                            
                                            <div class="incoming_msg">
                                            <div style="width:3%"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">  <label class="Name_font">vikash</label></div>
                                             
                                            <div class="custom-file">
                                                <span class="hiddenFileInput">
                                                    <input type="file" name="theFile"/>
                                                </span>
                                                
                                                <span class="time_date"> 11:01 AM    |    Today</span>
                                                <label >doc.pdf</label>
                                            </div>
                                            </div>
                                
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >Rahul</label> </div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span>
                                              
                                                </div>
                                              </div>
                                            </div>
                                            
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >vikash</label> </div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>We work directly with our designers and suppliers,
                                                    and sell direct to you, which means quality, exclusive
                                                    products, at a price anyone can afford.</p>
                                                  <span class="time_date"> 11:01 AM    |    Today</span></div>
                                              </div>
                                            </div>
                                          </div>
                                                                       
                                          <div class="type_msg">
                                            <div class="input_msg_write">
																							<input type="text" class="write_msg" id="message_input_box" placeholder="Type a message"/> 
																							<form title="multiple file uplaod" id="message-form-multi" class="message-form-multi" method="post" enctype='multipart/form-data'>
																								<input id="file1" type="file" name="attachment[]" class="fa fa-paperclip" aria-hidden="true" multiple="multiple"/>
																								<input type="hidden" name="tags_selected" id="tags_selected1" value=""/>
																								<input type="hidden" name="project_hidden_id" id="project_hidden_id1" value="<?php echo $project['id']; ?>"/>
																								<input type="hidden" name="user_type" id="user_type" value="company"/>
																								<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['company_sess']['id']; ?>"/>
                                              </form>
                                              <form title="single file uplaod" id="message-form" class="message-form" method="post" enctype='multipart/form-data'>
																								<input id="file" type="file" name="attachment" class="fa fa-paperclip" aria-hidden="true" />
																								<input type="hidden" name="tags_selected" id="tags_selected" value=""/>
																								<input type="hidden" name="project_hidden_id" id="project_hidden_id" value="<?php echo $project['id']; ?>"/>
																								<input type="hidden" name="user_type" id="user_type" value="company"/>
                                              </form>
                                             	<button class="msg_send_btn" onclick="sendMessageToDatabase()" id="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                            </div>
                                          </div>
                                         </div>
                                        </div>                        
                                                                  <!--- end ---->                    
                                    </div>
                              <div class="tab-pane" id="document">
                                  
                                  <br>
                                  <div class="container text-center">
                                      <div class="form-group">
                                        <input type="text" class="form-control" id="document_name" onkeyup="getTaskDocumentByName(this.value)" placeholder="Enter Document Name">
                                      </div>
                                      <div class="form-group">
                                        <select onchange="getTaskDocument(this.value)" id="tasks" class="form-control">
                                            <option value="-1">All</option>
                                            <?php
                                            
                                                foreach($task_list as $value)
                                                {
                                                    echo "<option value=".$value->taskId.">".$value->name."</option>";
                                                }
                                                
                                            ?>
                                        </select>
                                      </div>

                                    </div>
                                  
                                  <div class="row" style="margin-top:20px;" id="DocsSection">
                                      <?php
                                      
                                        foreach($project_docs as $projectDoc)
                                        { ?>
                                            <div style="margin-bottom:4px;" class="col-sm-6 text-center">
                                                
                                        <?php
                                        if(@is_array(getimagesize($projectDoc->file_path))){
                                                    $image = 1;
                                                } else {
                                                    $image = 0;
                                                }
                                            $newLink = "";
                                            if($image == 0){
                                                $newLink = $projectDoc->file_path;
                                            }else{
                                                $newLink = $projectDoc->file_path;
                                            }
                                            ?>
                                            
                                            <div class="custom-file" onclick="window.open('<?php echo $newLink; ?>', '_blank');">
                                                <span class="hiddenFileInput">
                                                </span>
                                                <span class="time_date"> <?php echo date( "M, d Y h:m A" ,strtotime($projectDoc->created_at)); ?>   </span>
                                                <label><?php echo $projectDoc->file; ?></label>
                                            </div>
                                            <?php
                                            if($image == 0)
										    { ?>
										    
										        <!--<object style="width:100%; overflow:hidden;" src="<?php echo $projectDoc->file ?>"><iframe style="height:400px;" src="https://docs.google.com/viewer?url=<?php echo $projectDoc->file_path; ?>&embedded=true"></iframe></object>-->
										        
										    <?php }else{ ?>
										      
										      <!--<img style="display:block;margin:auto;height:400px;" src="<?php echo $projectDoc->file_path; ?>" class="img-responsive"  alt="img">-->
                <!--                                  <?php echo $projectDoc->file; ?><br>-->
                <!--                                  <?php echo date( "M, d Y h:m A" ,strtotime($projectDoc->created_at)); ?>-->
										        
										<?php }
                                        
                                        ?>
                                            </div> 
                                        <?php }
                                      ?>
                                  </div>
                              </div>
                            </div>
						</div>
				    </div>
				</div>
		</div>
	</div>
</div>	
<?php } ?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="    height: 600px;
    overflow: scroll;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Task in <?= $project['name']; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <form action="Tasksapi/add" id="companytaskAddUpdate" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
                    
                    <div id="importedids" style="display:none;">
                                          
                    </div>
                    
                    <style>
                        .btn-default{
                            height:40px!important;
                            padding:10px!important;
                        }
                        .smart-form .checkbox input,.smart-form .radio input{position:unset!important;}
                        span.multiselect-native-select {
                        	position: relative
                        }
                        span.multiselect-native-select select {
                        	border: 0!important;
                        	clip: rect(0 0 0 0)!important;
                        	height: 1px!important;
                        	margin: -1px -1px -1px -3px!important;
                        	overflow: hidden!important;
                        	padding: 0!important;
                        	position: absolute!important;
                        	width: 1px!important;
                        	left: 50%;
                        	top: 30px
                        }
                        .multiselect-container {
                        	position: absolute;
                        	list-style-type: none;
                        	margin: 0;
                        	padding: 0
                        }
                        .multiselect-container .input-group {
                        	margin: 5px
                        }
                        .multiselect-container>li {
                        	padding: 0
                        }
                        .multiselect-container>li>a.multiselect-all label {
                        	font-weight: 700
                        }
                        .multiselect-container>li.multiselect-group label {
                        	margin: 0;
                        	padding: 3px 20px 3px 20px;
                        	height: 100%;
                        	font-weight: 700
                        }
                        .multiselect-container>li.multiselect-group-clickable label {
                        	cursor: pointer
                        }
                        .multiselect-container>li>a {
                        	padding: 0
                        }
                        .multiselect-container>li>a>label {
                        	margin: 0;
                        	height: 100%;
                        	cursor: pointer;
                        	font-weight: 400;
                        	padding: 3px 0 3px 30px
                        }
                        .multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
                        	margin: 0
                        }
                        .multiselect-container>li>a>label>input[type=checkbox] {
                        	margin-bottom: 5px
                        }
                        .btn-group>.btn-group:nth-child(2)>.multiselect.btn {
                        	border-top-left-radius: 4px;
                        	border-bottom-left-radius: 4px
                        }
                        .form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
                        	padding: 3px 20px 3px 40px
                        }
                        .form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
                        	margin-left: -20px;
                        	margin-right: 0
                        }
                    </style>
                    
                    
                    
                    <fieldset>
                        <div class="row">
                            <section class="col col-md-12">
                                <lable>Select Contractor</lable>
								<select id="dates-field2" class="multiselect-ui form-control" multiple="multiple" name="contractorIds[]"  >
								    <?php
                                        $contractor_list = $this->db->select('company_member_relations.type, contractor.*')
                                             ->from('company_member_relations')
                                             ->join('contractor', 'company_member_relations.member_id = contractor.id')
                                             ->where('company_member_relations.company_id',$company_id)
                                             ->where('contractor.is_role',1)
                                             ->where('company_member_relations.type','leadcontractor')->distinct()->get()->result();
                                        foreach($contractor_list as $contractor)
                                        {
                                            echo '<option value="'.$contractor->id.'">'.$contractor->owner_first_name.'</option>';
                                        }
                                    ?>
								</select>
								</label>
							</section>
                        </div>
                    </fieldset>
                    
						<fieldset>
								<div class="row">
									<section class="col col-md-12">
										<lable>Select Crew</lable>
											<select id="dates-field2" class="multiselect-ui form-control" multiple="multiple" name="crewIds[]"  >
													<?php
																$crew_member_list = $this->db->select('company_member_relations.type, crew_member.*')
																			->from('company_member_relations')
																			->join('crew_member', 'company_member_relations.member_id = crew_member.id')
																			->where('company_member_relations.company_id',$company_id)
																			->where('company_member_relations.type','crew')->distinct()->get()->result();
																foreach($crew_member_list as $crew_membe)
																{
																		echo '<option value="'.$crew_membe->id.'">'.$crew_membe->name.'</option>';
																}
														?>
											</select>
									</label>
								</section>
								</div>
						</fieldset>
                    
                    
                    
					<fieldset>
						<div class="row">
							<input type="hidden" name="project_id" value="<?= @$project['id']; ?>">
							<input type="hidden" name="company_id" value="<?= $company_id; ?>">
							
							<section class="col col-md-12">
								<label class="label">Task name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  value="" >
								</label>
							</section>				
						</div>
						<section>
						<label class="label">Description</label>
						<label class="textarea" >
						<textarea rows="3" maxlength="400" name="description" placeholder="Description"></textarea>
						</label>
						</section>
						
						<div class="row" style="display:none;">
							<section class="col col-md-12">
								<label class="label">Assign Contractor</label>
								<label class="input">
									<select name="contractor" class="form-control">
									    <?php
									        foreach($contractor_list as $value){
									            echo "<option value='".$value->id."'>".$value->owner_first_name."</option>";
									        }
									    ?>
									</select>
    						    </label>
								</label>
							</section>				
						</div>
						
						<div class="row" style="display:none;">
							<section class="col col-md-12">
							    <label class="label">Assign Crew Members</label>
							    <?php
							        foreach($crew_list as $value){
							            ?>
							            <input class="checkbox1" type="checkbox" name="myCheckboxes[]" value="<?php echo $value->id; ?>">
                                        <label for="vehicle1"><?php echo $value->name; ?></label>
							        <?php }
							    ?>
							</section>				
						</div>
						
						<input type="hidden" name="total_element_text" id="total_element_text" value="0"/>
						<input type="hidden" name="total_element_image" id="total_element_image" value="0"/>
						<input type="hidden" name="total_element_video" id="total_element_video" value="0"/>
						
						<div id="taskstepsdiv">
						    
						</div>
						
					</fieldset>	
					<footer>
						<button type="submit" id="submit" class="btn btn-primary">Save</button>
					</footer>
				</form>
		        <div class="row" style="margin:15px;">
            		<div class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
            		    <legend>
            			Steps To Complete Tasks <a href="javascript:void(0);" class="btn btn-labeled btn-info  pull-right" onclick="openActionOptionPreop();" id="layerOpt" data-id="show" > <span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add Steps </a>
            			</legend>								
            		</div>
            		<div class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
            		    <div class="col-sm-12 col-md-12 col-lg-12 choice" style="display:none">
            		        <div class="col-sm-6 col-md-6 col-lg-6 boxed" style="width:47%;margin-right: 20px;" onclick="openPrepopulatedList()">
                              Prepopulated
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 boxed" style="width:48%;" onclick="openCreateNew()">
                              Create New
                            </div>
            		    </div>
            		    <p class="Show_option" style="display: none;">
            			    <span class="pull-right" >
                				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="addAction('text');" > <span class="btn-label"><i class="fa fa-comment-o"></i></span> Text </a>&nbsp;&nbsp;/&nbsp;&nbsp;
                				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="addAction('image');" > <span class="btn-label"><i class="fa fa-file-image-o"></i></span> Image </a>&nbsp;&nbsp;/&nbsp;&nbsp;
                				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="addAction('video');" > <span class="btn-label"><i class="fa fa-file-video-o"></i></span> Video </a>
                			</span>
            			<hr>
            			</p>
            		</div>
            	</div>
      </div>
    </div>
    
    
    
  </div>
</div>
<!--- Exising --->
<div id="inviteExisingdModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invite People</h4>
      </div>
      <div class="modal-body" style="height:250px;">
        <div class="panel-group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#collapse1">+ Lead Contractor</a>
                </h4>
              </div>
              <div id="collapse1" class="panel-collapse collapse">
                <?php
                
                    foreach($existing_contractor as $value)
                    {
                        ?>
                        <div class="panel-body"><?php echo $value->owner_first_name; ?><button class="btn btn-success" onclick="inviteExisingPeople('<?php echo $value->owner_first_name; ?>','<?php echo $value->email; ?>','1','leadcontractor','<?php echo $value->id; ?>')" style="float:right;">Invite</button></div>
                        <?php
                    }
                ?>
              </div>
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#collapse2">+ Crew Member</a>
                  
                </h4>
              </div>
              <div id="collapse2" class="panel-collapse collapse">
                    <?php
                
                    foreach($existing_crew_member as $value)
                    {
                        ?>
                        <div class="panel-body"><?php echo $value->name; ?><button class="btn btn-success" onclick="inviteExisingPeople('<?php echo $value->name; ?>','<?php echo $value->email; ?>','3','crew','<?php echo $value->id; ?>')" style="float:right;">Invite</button></div>
                        <?php
                    }
                ?>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--- invite new modal ---->
<div id="inviteNewdModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invite People</h4>
      </div>
      <div class="modal-body">
            <form id="invitenewpeople" class="invitenewpeople" method="post">
              <div class="form-group">
                <label for="exampleInputEmail1">Email Name</label>
                <input type="text" name="name" class="form-control" id="invitenewpeoplename" aria-describedby="emailHelp" placeholder="Enter name" required="">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Enter Email</label>
                <input type="email" name="email" class="form-control" id="invitenewpeopleemail"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" placeholder="Enter email" required="">
              </div>
              
              <input type="hidden" name="is_for" value="project">
              <input type="hidden" name="project_id" value="<?= @$project['id']; ?>">
              <input type="hidden" name="sender_type" value="company">
              <input type="hidden" name="company_id" value="<?= $company_id; ?>">
              
              
              <div class="form-group">
                <label for="exampleInputPassword1">Select position</label>
                <select name="reciever_type" class="form-control" required="">
                    <option value="leadcontractor">Lead Contractor</option>
                    <option value="crew">Crew Member</option>
                </select>
              </div>
              <input type="submit" value="Invite" class="btn btn-primary"/>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- openPrepopulated Modal -->
<div id="PrepopulatedModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Prepopuldated Task</h4>
      </div>
      <div class="modal-body">
        <?php
            echo '<ul style="margin-top: 18px;height: 588px;
    overflow-y: scroll;">';
            foreach($pretask_list as $va1)
            {   
                $link = base_url('admin/task-detail/').encoding($va1->taskId);
                $task_name = $va1->name;
                $task_id = $va1->taskId;
                echo '<li class="list-group-item" ><input class="theClass" type="radio" id="'.$task_id.'" name="taskName" value="'.$task_id.'"> '.' <span style="margin-left: 5px;font-size: 16px;">'.$task_name.'</span>'.'<span style="float:right;"> <a data-toggle="collapse" href="#collapse'.$task_id.'">View</a></span></li>';
                
                $task_meta = $va1->task_meta;
                
                ?>
                
                    <div id="collapse<?php echo $va1->taskId; ?>" class="panel-collapse collapse">
                        <?php if(!empty($task_meta)): $colors = array('info', 'warning','success'); ?>
							<?php foreach ($task_meta as $sm => $step) { $rand_color = $colors[array_rand($colors)]; ?>
							<div class="alert alert-<?= $rand_color; ?>" data-metaid="<?= $step->taskmetaId; ?>"data-type="<?= $step->fileType; ?>">
								<?php if($step->fileType=='TEXT'):?>
									<p class="text-muted">
										<?= $step->description; ?> 
										<input type="hidden" id="filetext_<?= $step->taskmetaId; ?>" name="filetext" value="<?= $step->description; ?>" >
									</p>
								<?php endif; ?>
								<?php if($step->fileType=='IMAGE'):?>
									<img  width="300" height="250" src="<?= base_url('uploads/task_image/').$step->file; ?>" class="img-responsive"  alt="img">
											
								<?php endif; ?>
								<?php if($step->fileType=='VIDEO'):?>
									<div class="embed-responsive embed-responsive-16by9">
                                        <video width="420" height="315" controls="true" class="embed-responsive-item">
                                          <source  src="<?= base_url('uploads/task_video/').$step->file; ?>" type="video/mp4" />
                                        </video>
                                    </div>
											
								<?php endif; ?>
							</div>
							<?php } ?>
						<?php else: ?>
							<div style=" margin-bottom: 0;" class="alert alert-warning" data-metaid="87" data-type="VIDEO">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <h1>No Records Found</h1>
                                </div>
                			</div>
						<?php endif; ?>
                    

                    </div>
                
                <?php
                
                
            }
            echo '</ul>';
        ?>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="CopyMetaData()">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="add-data" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					Manage Steps
				</h4>
			</div>
			<div class="modal-body">
	           <!-- Add CUstomer -->
				<!-- widget content -->
				<div class="widget-body no-padding">
					<form action="Tasksapi/addTaskStep" id="create-task-step-company" class="smart-form" novalidate="novalidate" autocomplete="off">
				
						<fieldset>
						<input type="hidden" name="id" id="taskId_ss" value="<?php echo encoding($task['taskId']); ?>" >
						<input type="hidden" name="taskstepId" id="taskstepId" value="" >
							

							<div class="col-md-12 col-sm-12 col-lg-12" id="divPro_1">
								
								<section class="col col-md-12">
									<label class="label">TEXT<span class="error">*</span></label>
									<label class="textarea"><i class="icon-append fa fa-comment"></i>
											<textarea rows="4" class="textClassStep" name="textfile_1" id="textfile_1" placeholder="Enter Task Instructions step" maxlength="400"></textarea>
											<input type="hidden" id="textfileId_1" name="textfileId_1" value="0">
										</label>
								</section>
								
							</div>
							<div class="col-md-12 col-sm-12 col-lg-12" id="divProImg_1">

								<section class="col col-md-12 text-center">
									<label class="label"><strong>Image Preview</strong></label>
									<img width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Image+Preview"  id="blah_1" alt="img">

								</section>
								<section class="col col-md-12">
									<label class="label">Image<span class="error">*</span></label>
									<div class="input input-file">
									<input type="hidden" name="imagefileId_1" value="0">
									<span class="button"><input type="file" class="textClassStep" name="fileImage_1" id="file_1" onchange="readURL(this,1);this.parentNode.nextSibling.value = this.value" accept="image/*">Browse</span><input type="text" readonly="">
									</div>
								</section>
							</div>
							<div class="col-md-12 col-sm-12 col-lg-12" id="divProVideo_1">

								<section class="col col-md-12 text-center">
									<label class="label"><strong>Video Preview</strong></label>
									<div id="privew1"><img  width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Video+Preview"  alt="img"></div>
								</section>
							<section class="col col-md-12">
								<label class="label">Video<span class="error">*</span></label>
								<div class="input input-file">
								<input type="hidden" name="videofileId_1" value="0"><span class="button"><input type="file" class="textClassStep" name="videofile_1" id="videofile_1" onchange="filePreviewMain(this,1);this.parentNode.nextSibling.value = this.value" accept="video/*">Browse</span><input type="text" readonly="">
								</div>
							</section>
							</div>
		</div>
								
								
						</fieldset>

						<footer>
							<button type="submit" id="submit" class="btn btn-primary">
								Save
							</button>
						</footer>
					</form>
				</div>
				<!-- end widget content -->
				<!-- Add CUstomer -->
	        </div>
		</div>
	</div>
</div>
<!-- End modal -->
<div class="modal" tabindex="-1" role="dialog" id="confirmModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: black;color: white;font-size: 21px;width: 326px;height: 307px;margin: 0 auto;">
      <div class="modal-body">
        <p id="text_message">Is this related to particular task ?</p>
        <p id="text_tags">What task do you want to tag ?</p>
      </div>
      <div class="modal-footer" style="border:none;text-align:center;" id="confirm_footer">
          
        <div class="row" id="task_tag_list">
            <select onchange="submitform(this.value)" style="background: #1f1f33;color: white;">
                <option>Select Option</option>
                <?php
                    foreach($all_tag_list as $value)
                    { ?>
                        <option value="<?php echo $value->taskId; ?>"><?php echo $value->name; ?></option>
                    <?php }
                ?>
            </select>
            <?php
                foreach($task_list as $value)
                { ?>
                    <!--<div class="text-center col=sm-6">-->
                    <!--    <button onclick="submitform('<?php echo $value->taskId; ?>')" type="button" class="btn btn-primary" style="padding: 10px 33px;"><?php echo $value->name; ?></button>-->
                    <!--</div>-->
                <?php }
            ?>
        </div>
          
        <button type="button" class="btn btn-primary confirmbtns" onclick="showtasktaglist()" style="padding: 10px 33px;">Yes</button>
        <button type="button" class="btn btn-primary confirmbtns" onclick="submitform()" data-dismiss="modal" style="padding: 10px 33px;">No</button>
      </div>
    </div>
  </div>
</div>
<input id="clicker" type="checkbox" style="display:none;"/>
<label for="clicker" style="display:none;">Click me! I'm an arbitrary trigger</label>
<div class="panel-wrap">
  <div class="panel" >
    <div class="col-sm-12" style="margin-bottom:30px;">
        
        <div class="row" style="margin-top: 50px;
            margin-bottom: 20px;
            color: #736c6c;">
            <div class="col-sm-6" style="text-align:left;">
                <h4>Profile</h4>
            </div>
            <div class="col-sm-6" style="text-align:right;">
                <i class="fa fa-2x fa-times" onclick="closewrap()"></i>    
            </div>
            
        </div>
        <div class="card" style="border: none;">
            <img class="card-img-top" src="https://img.icons8.com/officel/2x/user.png" alt="Card image" style="width:100%;border:none;background: #d85e5e;border-top-left-radius: 10px;border-top-right-radius: 10px;">
            <div class="card-body" style="border-bottom-left-radius: 10px;border: 1px solid #eadfdf;border-bottom-right-radius: 10px;">
                <h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;font-size:16px;">
                    <span class="profile_name">Ankit Rathore</span><br>
                    <span class="profile_position">Web Developer</span>
                    <span id="delete_event" style="text-align: right;color: red;float: right;">
                        <i id="actionIds" onclick="" class=""></i>
                    </span><br>
                </h4>
            </div>
        </div>
        
        <div class="col-sm-12">
            
            
            <h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;font-size:16px;text-align:left;margin-top:30px;">
                <span style="font-size:16px;;color: grey;">Display name </span><br>
                <span class="profile_name">Ankit Rathore </span><br>
                <span style="font-size:16px;;color: grey;">email </span><br>
                <span class="profile_email">rathoreankit582@gmail.com</span>
            </h4>
        </div>
    </div>
  </div>
</div>
<script>

    $(document).on("keyup", "#message_input_box", function(e){
        if (e.key === 'Enter' || e.keyCode === 13){
            sendMessageToDatabase();
        }
    });
    
    
    $( document ).ready(function() {
        searchmembers();
    });
    
    function searchmembers(value)
    {
        let project_id = $('#hiddenId').val();
        $.ajax({
            url:"<?php echo site_url('company/Project/searchmembers'); ?>",
            type: "post",
            dataType: 'json',
            data: {value:value,project_id:project_id},
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                console.log(res);
                $('#involved_members').html("");
                $('#noninvolved_members').html("");
                
                $('#involved_members').html('<div><h4 style="font-weight: 500; margin-left: 15px; margin-bottom: 10px;">'+res.invite_peoples_count+' members</h4></div>');
                $('#involved_members').append(res.involved_members);
                
                
                $('#noninvolved_members').html('<div style="margin-left: 15px;margin-top: 20px;margin-right: 15px;margin-bottom: 13px;font-size: 20px;><span style="font-weight: 500;">Removed members</span></div>');
                $('#noninvolved_members').append('<div><h4 style="font-weight: 500; margin-left: 15px; margin-bottom: 10px;">'+res.nnoninvite_peoples_count+' members</h4></div>');
                $('#noninvolved_members').append(res.noninvolved_members);
                if(res.status=='success'){
                  
                }else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                preLoadshow(false);
            }
        });
    }
    function closewrap()
    {
        $("#clicker").prop("checked", false);
    }
    
    function getprofiledetail(name,role,email,actionId,is_removed="")
    {
        $("#clicker").prop("checked", false);
        if(is_removed == 0)
        {
            $('#actionIds').removeClass('fa fa-plus');
            $('#actionIds').addClass('fa fa-trash');
            $('#delete_event').css('color','red');
        }else{
            $('#actionIds').removeClass('fa fa-trash');
            $('#actionIds').addClass('fa fa-plus');
            $('#delete_event').css('color','green');
        }
        value = $('#action_'+actionId).attr('onclick');
        $('#actionIds').attr('onclick',value)
        console.log(value)
        $('.profile_name').html(name);
        $('.profile_position').html(role);
        $('.profile_email').html(email);
        $("#clicker").prop("checked", true);
    }

    function showtasktaglist(){
        $('#text_message').css('display','none');
        $('#text_tags').css('display','block');
        $('#task_tag_list').css('display','block');
        $('.confirmbtns').css('display','none');
    }

    function submitform(id=""){
        if(id==""){
					if(document.getElementById("file1").value != "") {
						$('#message-form-multi').submit();
					}else{
						$('#message-form').submit();
					}
        }else{
					if(document.getElementById("file1").value != "") {
						$('#tags_selected1').val(id);
						$('#message-form-multi').submit();
					}else{
						$('#tags_selected').val(id);
						$('#message-form').submit();
					}
        }
    }

    function inviteExisingPeople(invitenewpeoplename,invitenewpeopleemail,invitenewpeopleposition,role,userid="")
    {
        let company_id = '<?php echo $_SESSION['company_sess']['id']; ?>';
        let project_id = $('#hiddenId').val();
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/inviteNewPeople'); ?>",
            type: "post",
            dataType: 'json',
            data: {invitenewpeoplename: invitenewpeoplename,invitenewpeopleemail: invitenewpeopleemail,invitenewpeopleposition: invitenewpeopleposition,is_for:'task',project_id:project_id,role:role,company_id:company_id,userid:userid,sender_type:'company'},
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                  //window.location.reload();
                }else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                preLoadshow(false);
            }
        });
    }
    
    // getmessages();
    // setInterval(function()
    // { 
    //     getmessages();
    // }, 3000);
    
    // function getmessages(){
    //     //$('#message_collection').html('')
    //     $.ajax({
    //         url:"<?php echo site_url('chat/Api/getmessages'); ?>",
    //         type: "post",
    //         dataType: 'json',
    //         data: {project_hidden_id:'<?php echo $project['id']; ?>'},
    //         success:function(res){
    //             $('#message-area-container').html(res.messages)
    //             //message-area-container
    //         }
    //     });
    // }
    
    getsentmessages();
    setInterval(function()
    { 
        getsentmessages();
    }, 3000);
    
    function getsentmessages(){
        let search_for_task_chat = $('#search_for_task_chat').val();
        let search_for_task_chat_docs = $('#search_for_task_chat_docs').val();
        $.ajax({
            url:"<?php echo site_url('chat/Api/getsentmessages'); ?>",
            type: "post",
            dataType: 'json',
            data: {project_hidden_id:'<?php echo $project['id']; ?>',search_for_task_chat:search_for_task_chat,search_for_task_chat_docs:search_for_task_chat_docs},
            success:function(res){
                $('.msg_history').html(res.messages);
                //message-area-container
            }
        });
    }
    
    function openOptionsbtn(){
        console.log('Hello');
        $('#openclosebtn').css('display','none');
        $('.optionbtn').css('display','inline-block');
    }
    
    $(document).on('click', function (e) {
        if ($(e.target).closest("#openclosebtn").length === 0) {
            $('#openclosebtn').css('display','inline-block');
            $('.optionbtn').css('display','none');
        }
    });

    
    function showpopup(){
        $('#text_message').css('display','block');
        $('#text_tags').css('display','none');
        $('#task_tag_list').css('display','none');
        $('.confirmbtns').css('display','inline-block');
        $('#confirmModal').modal('show');
    }
    
    $('#message-form').submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url:"<?php echo site_url('chat/Api/message'); ?>",
            type: "post",
            dataType: 'json',
            data: new FormData(this),
             processData: false,
             contentType: false,
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                getTaskDocumentByName($('#document_name').val());
                $('#file').val('');
                getsentmessages();
                $('#confirmModal').modal('hide');
                preLoadshow(false);
                scrolled = false;
            }
        });
    })
    
    
    function sendMessageToDatabase(message="",user_type="company",tag="")
    {
        project_hidden_id = '<?php echo $project['id']; ?>';
        message = $('#message_input_box').val();
        if(message == "")
        {
            swal('You can not send blank message');
        }else{
            $.ajax({
                url:"<?php echo site_url('chat/Api/sendMessageToDatabase'); ?>",
                type: "post",
                data: {message:message,project_hidden_id:project_hidden_id,user_type:user_type,tags_selected:tag},
                beforeSend  : function() {
                    preLoadshow(true);
                }, 
                success:function(res){
                    $('#message_input_box').val("");
                    getsentmessages();
                    $('#confirmModal').modal('hide');
                    preLoadshow(false);
                    scrolled = false;
                }
            });   
        }
    }
    
    setInterval(updateScroll,1000);
    
    var scrolled = false;
    function updateScroll(){
        if(!scrolled){
            var element = document.getElementById("yourDivID");
            element.scrollTop = element.scrollHeight;
        }
    }
    
    $("#yourDivID").on('scroll', function(){
        scrolled=true;
        console.log(scrolled);
    });
    
    function getmessagesFromDatabase()
    {
        let search_for_task_chat = $('#search_for_task_chat').val();
        let search_for_task_chat_docs = $('#search_for_task_chat_docs').val();
        $.ajax({
            url:"<?php echo site_url('chat/Api/getmessagesFromDatabase'); ?>",
            type: "post",
            dataType: 'json',
            data: {project_hidden_id:'<?php echo $project['id']; ?>',search_for_task_chat_docs:search_for_task_chat_docs,search_for_task_chat:search_for_task_chat},
            success:function(res){
                return res;
            }
        });
    }
    
    $('.invitenewpeople').submit(function(e){
        e.preventDefault();
        
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/inviteNewPeopleCreateandSend'); ?>",
            type: "post",
            dataType: 'json',
            processData: false,
            contentType: false,
            data: new FormData($('.invitenewpeople')[0]),
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                }else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                preLoadshow(false);
            }
        });
    });
    

    function openModel(){
        $('#inviteNewdModal').modal('show')
    }
    
    function openExistingModel(){
        $('#inviteExisingdModal').modal('show')
    }
    
    function removePeople(inviteId)
    {
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/removePeople') ?>",
            type: "post",
            dataType: 'json',
            data: {inviteId: inviteId},
            success:function(result){
                toastr.clear();
                toastr.success('People Removed Successfully.', 'Success', {timeOut: 3000});
                searchmembers($('#thisissearchfield').val());

                //setTimeout(function(){ window.location.reload(); }, 3000);
            }
        });
    }
    
    function addPeople(inviteId)
    {
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/addPeople') ?>",
            type: "post",
            dataType: 'json',
            data: {inviteId: inviteId},
            success:function(result){
                toastr.clear();
                toastr.success('People Added Successfully.', 'Success', {timeOut: 3000});
                searchmembers($('#thisissearchfield').val());
                //setTimeout(function(){ window.location.reload(); }, 3000);
            }
        });    
    }

    function CopyMetaData(){
        var taskId = $("input[name='taskName']:checked").val();
        console.log(taskId);
        if(taskId == undefined || taskId == "undefined")
        {
            toastr.error('please Select Any Task', 'Alert!', {timeOut: 4000});  
        }else{
            $.ajax({
                url:"<?php echo site_url('company/Project/gettaskMetaData') ?>",
                type: "post",
                dataType: 'json',
                data: {taskId: taskId},
                beforeSend  : function() {
                    preLoadshow(true);
                }, 
                success:function(result){
                    $('#importedids').append('<input value="'+taskId+'" type="text" name="importedids[]"/>')
                    for(let i =0; i < result.length; i++){
                        console.log(result[i]['fileType']);
                        if(result[i]['fileType'] == 'TEXT'){
                            var total_element_text = $('#total_element_text').val();
                            total_element_text = parseInt(total_element_text) + 1;
                            $('#total_element_text').val(total_element_text);
                            $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divPro_1"><section class=""><label class="label">TEXT<span class="error">*</span></label><label class="textarea"><i class="icon-append fa fa-comment"></i><textarea rows="4" class="textClassStep" name="textfile_'+total_element_text+'" id="textfile_1" placeholder="Enter Task Instructions step" maxlength="400">'+result[i]['description']+'</textarea><input type="hidden" id="textfileId_'+total_element_text+'" name="textfileId_1" value="0"></label></section></div>');
                        }
                        if(result[i]['fileType'] == 'IMAGE'){
                            let imageurl = base_url+'uploads/task_image/'+result[i]['file'];
                            $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" ><img width="400" height="300" src="'+imageurl+'?text=Image+Preview" alt="img"></div>');
                        }
                        if(result[i]['fileType'] == 'VIDEO'){
                            let imageurl = base_url+'uploads/task_video/'+result[i]['file'];
                            $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12"><video width="420" height="315" controls="true" class="embed-responsive-item"><source src="'+imageurl+'" type="video/mp4"></video></</div>');
                        }
                        
                    }
                    $('#PrepopulatedModal').modal('hide');
                    $('.Show_option').css('display','none');
                    $('.choice').css('display','none');
                    preLoadshow(false);
                }
            });    
        }
        
    }

    function openPrepopulatedList(){
        $('.choice').css('display','none');
        $('#PrepopulatedModal').modal('show');
    }

    function openCreateNew(){
        $('.choice').css('display','none');
        $('.Show_option').css('display','block');
    }

    function openActionOptionPreop(){
        // $('.choice').css('display','block');
        $('.choice').toggle();
    }

    function getCheck()
    {
        var checkedVals = $('.theClass:checkbox:checked').map(function() {
            return this.value;
        }).get();
        
        checkboxVals = checkedVals.join(",");
        $.ajax({
            url:"<?php echo site_url('company/Project/prepopulatedTask') ?>",
            type: "post",
            dataType: 'json',
            data: {checkboxVals: checkboxVals},
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(result){
                preLoadshow(false);
                $('#DocsSection').html(result);
            }
        });
        //alert(checkedVals.join(","));
    }
    
    function openAddPeopleSection() {
        $('#add_people_section').toggle();
    }

    function addAction(expression)
    {
        switch(expression) {
            case 'text':
                var total_element_text = $('#total_element_text').val();
                total_element_text = parseInt(total_element_text) + 1;
                $('#total_element_text').val(total_element_text);
                $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divPro_1"><section class=""><label class="label">TEXT<span class="error">*</span></label><label class="textarea"><i class="icon-append fa fa-comment"></i><textarea rows="4" class="textClassStep" name="textfile_'+total_element_text+'" id="textfile_1" placeholder="Enter Task Instructions step" maxlength="400"></textarea><input type="hidden" id="textfileId_'+total_element_text+'" name="textfileId_1" value="0"></label></section></div>');
            break;
            case 'image':
                var total_element_image = $('#total_element_image').val();
                total_element_image = parseInt(total_element_image) + 1;
                $('#total_element_image').val(total_element_image);
                $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divProImg_'+total_element_image+'"><section class="col col-md-12 text-center"><label class="label"><strong>Image Preview</strong></label><img width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Image+Preview"  id="blah_'+total_element_image+'" alt="img"></section><section class="col col-md-12"><label class="label">Image<span class="error">*</span></label><div class="input input-file"><input type="hidden" name="imagefileId_'+total_element_image+'" value="0"><span class="button"><input type="file" class="textClassStep valid" name="fileImage_'+total_element_image+'" id="file_'+total_element_image+'" onchange="readURL(this,'+total_element_image+');this.parentNode.nextSibling.value = this.value" accept="image/*">Browse</span><input type="text" readonly=""></div></section></div>');
            break;
            case 'video':
                var total_element_video = $('#total_element_video').val();
                total_element_video = parseInt(total_element_video) + 1;
                 var x = 1;
                $('#total_element_video').val(total_element_video);
                $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divProVideo_'+total_element_video+'"><a style="margin-left: 402px;" href="javascript:void(0);" class="fa fa-minus-circle remove_field">Remove</a><section class="col col-md-12 text-center"><label class="label"><strong>Video Preview</strong></label><div id="privew'+total_element_video+'"><img  width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Video+Preview"  alt="img"></div></section><section class="col col-md-12"><label class="label">Video<span class="error">*</span></label><div class="input input-file"><input type="hidden" name="videofileId_'+total_element_video+'" value="0"><span class="button"><input type="file" class="textClassStep" name="videofile_'+total_element_video+'" id="videofile_'+total_element_video+'" onchange="filePreviewMain(this,'+total_element_video+');this.parentNode.nextSibling.value = this.value" accept="video/*">Browse</span><input type="text" readonly=""></div></section></div>');
            break;
        } 

           $(taskstepsdiv).on("click",".remove_field", function(e){ 
                  e.preventDefault();
           $(this).parent('div').remove(); //remove inout field
           x--; //inout field decrement
              })  
      }
    
    function getTaskDocument(taskId)
    {
        id = $('#hiddenId').val();
        $.ajax({
            url:"<?php echo site_url('company/Project/getFileterTask') ?>",
            type: "post",
            dataType: 'json',
            data: {taskId: taskId,id:id},
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(result){
                preLoadshow(false);
                if(result != "")
                $('#DocsSection').html(result);
                else
                $('#DocsSection').html('<div class="container text-center"><h1>No documents found.</h1></div>');
            }
        });
    }
    
    function getTaskDocumentByName(file)
    {
        id = $('#hiddenId').val();
        $.ajax({
            url:"<?php echo site_url('company/Project/getTaskDocumentByName') ?>",
            type: "post",
            dataType: 'json',
            data: {file: file,id:id},
            beforeSend  : function() {
                preLoadshow(true);
            },
            success:function(result){
                preLoadshow(false);
                $('#DocsSection').html(result);
            }
        });
    }
    

    function ChangeTaskStatus(taskId)
    {
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/ChangeTaskStatus') ?>",
            type: "post",
            dataType: 'json',
            data: {taskId: taskId},
            success:function(result){
                toastr.clear();
                toastr.success('Task Status Changed Successfully.', 'Success', {timeOut: 3000});
                setTimeout(function(){ window.location.reload(); }, 3000);
            }
        });
    }

		function ChangeTaskApprovel(taskId)
		{
			$.ajax({
            url:"<?php echo site_url('company/Projectapi/ChangeTaskApprovel') ?>",
            type: "post",
            dataType: 'json',
            data: {taskId: taskId},
            success:function(result){
                toastr.clear();
                toastr.success('Task Status Changed Successfully.', 'Success', {timeOut: 3000});
                setTimeout(function(){ window.location.reload(); }, 3000);
            }
        });
		}

    function removeContractor(taskId,ContractorId)
    {
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/removeContractor') ?>",
            type: "post",
            dataType: 'json',
            data: {taskId: taskId, ContractorId: ContractorId},
            success:function(result){
                toastr.clear();
                toastr.success('Contractor Removed Successfully.', 'Success', {timeOut: 3000});
                setTimeout(function(){ window.location.reload(); }, 3000);
            }
        });
    }
    function removeCrewMember(taskId,CrewMemberId)
    {
        $.ajax({
            url:"<?php echo site_url('company/Projectapi/removeCrewMember') ?>",
            type: "post",
            dataType: 'json',
            data: {taskId: taskId, CrewMemberId: CrewMemberId},
            success:function(result){
                toastr.clear();
                toastr.success('Person Removed Successfully.', 'Success', {timeOut: 3000});
                setTimeout(function(){ window.location.reload(); }, 3000);
                //window.location.reload();
            }
        });
    }
    
    $("#camera").click(function () {
      $("input[name='attachment']").trigger('click');
    });
    
    $("input[name='attachment']").change(function(){
        showpopup();
    });

		$("input[name='attachment[]']").change(function(){
				//$('#message-form-multi').submit();
        showpopup();
    });

		$('#message-form-multi').submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url:"<?php echo site_url('chat/Api/multiDocsUpload_web'); ?>",
            type: "post",
            dataType: 'json',
            data: new FormData(this),
             processData: false,
             contentType: false,
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                getTaskDocumentByName($('#document_name').val());
                $('#file1').val('');
                getsentmessages();
                $('#confirmModal').modal('hide');
                preLoadshow(false);
                scrolled = false;
            }
        });
    })

</script>

	