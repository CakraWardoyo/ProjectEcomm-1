

  <div class="search-control">
    <div class="search-box">
      <form action="" id="form-search-c">
        <input type="text" placeholder="Search..." autocomplete="off" id="input-search-c">
        <button>
          <i class="fas fa-search"></i>
        </button>
      </form>
      <div class="search-results" id="search-results-c" style="display: none;"></div>
    </div>
  </div>

  <div class="profile-container chat">
    <?php $this->load->view('user toko online/sidebar'); ?>

    <div class="main-content-container">
      <div class="container-chat-user">
        <div class="chat-member-container">
          <div class="title">
            <h2>Chat</h2>
          </div>
          <div class="chat-member"></div>
        </div>
        <div class="chat-content-container start">
          <div class="start-to-chat">
            <img src="<?= base_url('assets/Project Toko Online/icon/chat.svg'); ?>">
            <h3>Mari memulai obrolan!</h3>
            <p>Silahkan memilih pesan untuk memulai percakapan</p>
          </div>
          <!-- <div class="chat-content-header">
            <div class="shop-info">
              <button class="back">
                <i class="fas fa-arrow-left back"></i>
              </button>
              <div class="container-image">
                <div class="circle-image">
                  <span class="chat-status"></span>
                  <img src="<?= base_url('assets/Project Toko Online/image/users/foto profile.png'); ?>">
                  <i class="fas fa-store"></i>
                </div>
              </div>
              <div class="more-info">
                <span>
                  Toko Laris
                </span>
                <span>
                  Online
                </span>
              </div>
            </div>
            <div class="more-action">
              <button class="delete-action">
                <i class="fas fa-ellipsis-v delete-action"></i>
                <div class="action-container">
                  <div class="action-list delete">
                    Hapus
                  </div>
                </div>
              </button>
            </div>
          </div> -->
          <!-- <div class="chat-content"> -->
            <!-- <div class="loading-chat">
              <div class="📦"></div>
              <div class="📦"></div>
              <div class="📦"></div>
              <div class="📦"></div>
              <div class="📦"></div>
            </div> -->
            <!-- <div class="chat-logs-container">
              <div class="chat-log-list">
                <div class="chat-date">
                  26 April 2099
                </div>
              </div>
              <div class="chat-log-list">
                <div class="chat-wrapper">
                  <p class="chat-message">
                    gan masih ada stok ga?
                  </p>
                  <span class="message-time">
                    15:32
                  </span>
                </div>
              </div>
              <div class="chat-log-list sender">
                <div class="chat-wrapper">
                  <p class="chat-message">
                    ada mas, butuh berapa?
                  </p>
                  <span class="message-time">
                    15:32
                  </span>
                </div>
              </div>
              <div class="chat-log-list">
                <div class="chat-wrapper">
                  <p class="chat-message">
                    2 pasang
                  </p>
                  <span class="message-time">
                    15:32
                  </span>
                </div>
              </div>
              <div class="chat-log-list sender">
                <div class="chat-wrapper">
                  <p class="chat-message">
                    woke
                  </p>
                  <span class="message-time">
                    15:32
                  </span>
                </div>
              </div>
              <div class="chat-log-list sender">
                <div class="chat-wrapper product-wrapper">
                  <div class="product-container">
                    <div class="image">
                      <img src="<?= base_url('assets/Project Toko Online/image/products/p1_358.jpg'); ?>">
                    </div>
                    <div class="info">
                      <span>
                        Ventela BTS High
                      </span> 
                      <span>
                        36
                      </span>
                    </div>
                  </div>
                  <span class="message-time">
                    15:32
                  </span>
                </div>
              </div>
              <div class="chat-log-list">
                <div class="chat-wrapper product-wrapper">
                  <div class="product-container">
                    <div class="image">
                      <img src="<?= base_url('assets/Project Toko Online/image/products/p1_358.jpg'); ?>">
                    </div>
                    <div class="info">
                      <span>
                        Patrobas Equip High
                      </span> 
                      <span>
                        36
                      </span>
                    </div>
                  </div>
                  <span class="message-time">
                    15:32
                  </span>
                </div>
              </div>
            </div> -->
          <!-- </div> -->
          <!-- <div class="chat-content-footer">
            <textarea class="chat-input-area" placeholder="Silahkan Nyangkem Disini" rows="1"></textarea>
            <button>
              <img src="<?= base_url('assets/Project Toko Online/icon/send.svg'); ?>">
            </button>
          </div> -->
        </div>
      </div>
    </div>
  </div>
  
  <div class="black-background-sidebar"></div>

  <div class="popup-container"></div>

  <div class="notification-container">
    <p></p>
  </div>

  <div class="distance-contents"></div>

  <?php	$this->load->view('user toko online/footer_rules'); ?>

  
  <script src="<?= base_url('assets/Project Toko Online/js/user/SideBarActive.js'); ?>"></script>
  <script src="<?= base_url('assets/Project Toko Online/js/user/Chat.js'); ?>"></script>

  
