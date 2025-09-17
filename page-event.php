<?php get_header(); ?>

<main>
    <div class="event-container" role="main">
        <section class="cards event-cards" aria-label="イベントカード">

            <!-- Card 1 -->
            <article class="event-card">
                <div class="card__body">
                    <figure class="card__img">
                        <picture>
                            <source media="(min-width: 781px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sun.png">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sp-sun.png" alt="サクラボ">
                        </picture>
                    </figure>
                    <div class="event-txt">
                        <h2 class="card__title">プロナビ・ハッカソン第1回</h2>
                        <p class="date">制作期間：2025年4月21日〜2025年5月12日</p>
                        <p class="theme">テーマ：「オフ会をアピールするHP」</p>
                        <p class="desc">Pronaviの公式のオフ会を2025年7月に開催するにあたり
                            オフ会に参加したくなるようなHPを作成することを目的に
                            3人1組でハッカソンを開催しました
                            全8組、23名の方に参加していただきました！</p>
                        <div class="card__actions">
                            <a class="btn" href="https://red719289.studio.site/" target="_blank" rel="noopener">作品ページはこちらから</a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Card 2 -->
            <article class="event-card">
                <div class="card__body">
                    <figure class="card__img">
                        <picture>
                            <source media="(min-width: 781px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/global.png">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sp-global.png" alt="グローバル">
                        </picture>
                    </figure>
                    <div class="event-txt">
                        <h2 class="card__title">プロナビ・ハッカソン第2回</h2>
                        <p class="date">制作期間：2025年7月14日(月)～2025年8月14日(月)</p>
                        <p class="theme">テーマ：「Metaleafのアピール用ホームページ」</p>
                        <p class="desc">開発はMetalife上で行い、Git/GitHubを活用してチームでの共同開発を実践。全員が実装と発表に関わる実践形式で進行。
                            最終日の成果発表会では投票により優勝チームを決定。第一回を大きく上回る24組、総勢72人ものメンバーが参加。</p>
                        <div class="card__actions">
                            <a class="btn" href="https://white819139.studio.site/" target="_blank" rel="noopener">作品ページはこちらから</a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Card 3 -->
            <article class="event-card">
                <div class="card__body">
                    <figure class="card__img">
                        <picture>
                            <source media="(min-width: 781px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sazanka.png">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sp-sazanka.png" alt="サザンカ">
                        </picture>
                    </figure>
                    <div class="event-txt">
                        <h2 class="card__title">サクラボ・コンペ第1回</h2>
                        <p class="date">制作期間：2025年7月14日(月)～2025年8月14日(月)</p>
                        <p class="theme">テーマ：「主婦向けWeb制作スクールのLP」</p>
                        <p class="desc">ターゲットは25～40歳前後の主婦層。
                            オンライン・個別指導型のWeb制作スクールを訴求するLPを新規作成する。
                            未経験でも安心できる学習環境や、現役エンジニア講師による個別指導、案件獲得までのサポートを訴求ポイントとする。</p>
                        <div class="card__actions">
                            <a class="btn" href="https://preview.studio.site/live/9YWyQxm2WM" target="_blank" rel="noopener">作品ページはこちらから</a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Coming soon -->
            <article class="event-card">
                <div class="card__body">
                    <figure class="card__img">
                        <picture>
                            <source media="(min-width: 781px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/comingsoon.png">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sp-comingsoon.png" alt="coming soon">
                        </picture>
                    </figure>
                    <div class="event-txt">
                        <h2 class="card__title">プロナビ・ハッカソン第3回</h2>
                        <p>乞うご期待！</p>
                    </div>
                </div>
            </article>

        </section>
    </div>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>