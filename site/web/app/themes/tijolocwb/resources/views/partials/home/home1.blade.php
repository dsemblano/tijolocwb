<section id="tela1" class="home-section bg-neutral-700 bg-no-repeat bg-bottom lg:bg-right-top py-6">
    <div class="container mx-auto">
        <div class="flex flex-col lg:flex-row gap-x-10 text-white">
            <div class="left flex flex-col w-full lg:w-1/3">

                <article class="text-center lg:text-left text-lg">
                    <p class="mb-6 animate__animated animate__fadeInLeftBig animate__fast">
                        O Tijolo Restaurante é um projeto de comida descomplicada, no centro histórico de Curitiba.
                        Cozinha contemporânea, baseado no slow food, na produção com cuidado e atenção plena.
                    </p>
                    <p class="mb-6 animate__animated animate__fadeInLeftBig animate__fast animate__delay-1s">
                        Servimos carnes, vinhos, massas, bocaditos, sanduíches, pastas e molhos especiais, produzidos
                        com insumos não refinados e minimamente processados.
                    </p>
                    <p class="mb-6 animate__animated animate__fadeInLeftBig animate__fast animate__delay-2s">
                        Grande parte do nosso cardápio é vegano, e nossa confeitaria preza por doces plant based (à base
                        de plantas).</p>

                    Faça sua reserva abaixo!
                    </p>
                </article>

                <div
                    class="grid justify-center lg:justify-start mt-6 animate__animated animate__headShake animate__delay-3s">
                    <div
                        class="bg-tijolo hover:bg-tijolopink text-2xl text-neutral-700 font-bold hover:text-white py-4 px-8 border border-tijolopink hover:border-transparent rounded lg:w-auto w-full">
                        <a href="/reservas/" class="w-full flex">
                            Reservas
                        </a>
                    </div>
                    {{-- @include('partials/snippets/reservation') --}}
                </div>
            </div>
            <div class="right mt-8 lg:mt-0 lg:w-2/3">
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
