<section id="tela1"
    class="home-section bg-tijologreentext bg-no-repeat bg-bottom lg:bg-right-top py-6 prose-h2:text-4xl">
    <div class="container mx-auto">
        <div class="flex flex-col lg:flex-row gap-x-10 text-tijologreentextp">
            <div class="left flex flex-col w-full lg:w-1/3 order-2 lg:order-1">

                <article class="text-left text-lg">
                    <p class="mb-6 animate__animated animate__fadeInLeftBig animate__fast">
                        O Tijolo Restaurante é um projeto de comida descomplicada, no centro histórico de Curitiba.
                        Cozinha contemporânea num ambiente único, numa das ruas mais antigas da cidade, dentro de um
                        prédio que ja foi casa do artista Ricardo Tod, autor do “Cavalo Babão”, a Fonte da Memória, obra
                        situada no coração do Largo da Ordem, em Curitiba.
                    </p>
                    <details>
                        <summary>Mais</summary>
                        <p> Gastronomia , arte e história juntas.
                            Servimos carnes, vinhos, massas, petiscos com opções vegetarianas e veganas. Pratos
                            tradicionais
                            do estado e uma carta de vinhos brasileiros dão o tom da cultura local, dentro do nosso
                            refúgio
                            urbano. Venha com calma, seja sempre muito bem vinda.</p>
                    </details>
                    </p>
                </article>



                <div
                    class="grid justify-center lg:justify-start mt-6 animate__animated animate__headShake animate__delay-3s">
                    <div
                        class="bg-tijolopinkhover hover:bg-tijolopink text-2xl text-tijologreentextp hover:text-tijolotext font-bold py-4 px-8 rounded lg:w-auto w-full">
                        <a href="/reservas/" class="w-full flex">
                            Reservas
                        </a>
                    </div>
                    {{-- @include('partials/snippets/reservation') --}}
                </div>
            </div>
            <div class="right mt-8 lg:mt-0 lg:w-2/3 order-1 lg:order-2 mb-6 lg:mb-0">
                {!! do_shortcode('[carousel_slide id="3832"]') !!}
            </div>
            {{-- <picture class="right mt-8 lg:mt-0 animate__animated animate__fadeInUp animate__slow lg:w-1/2">
        <source media="(max-width: 600px)" srcset="@asset('images/TijoloEntrada.webp')" width="570" height="545">
        <source media="(min-width: 601px)" srcset="@asset('images/TijoloEntrada.webp')" width="604" height="650">
        <img src="@asset('images/TijoloEntrada.webp')" alt="Tijolo CWB restaurante foto interior" width="604"
          height="650">
      </picture> --}}

        </div>

    </div>


</section>
