@include('partials/snippets.appheightjs')

<section id="tela1" class="home-section bg-neutral-700 bg-no-repeat bg-bottom lg:bg-right-top py-6">
  <div class="container mx-auto">
    <div class="flex flex-col lg:flex-row gap-x-10 text-tijolo">
      <div class="left flex flex-col w-full lg:w-1/2">
        {{-- <div class="logohome text-5xl text-center xl:text-7xl mb-6">
          <h2 class="text-5xl">A gente cozinha pra todo mundo</h2>
          <span class="text-2xl lg:text-5xl absolute top-[90%] lg:top-[95%] right-0">comidas e vinhos</span>
        </div> --}}

        <article class="text-center lg:text-left text-lg lg:text-2xl">
          <p class="mb-6">
            O Restaurante Tijolo CWB é um projeto de comida descomplicada, no centro histórico de Curitiba. Nosso conceito é baseado no slow food, na experiência, na produção com cuidado e atenção plena, sem a pressa do fast food e do cotidiano.
          </p>
          <p class="mb-6 animate__animated animate__fadeInUp animate__fast animate__delay-1s">
            Servimos carnes, vinhos, massas, bocaditos, sanduíches, pastas e molhos especiais, produzidos com insumos não refinados e
            minimamente processados.</p>
          <p class="mb-6 animate__animated animate__fadeInUp animate__fast animate__delay-2s">
            Trabalhamos com a ideia de comida saudável e saborosa, oferecemos comida de verdade. Grande
            parte do nosso cardápio é vegano, e nossa confeitaria preza por doces plant based.</p>

            Faça sua reserva abaixo!
          </p>
        </article>

        <div class="grid justify-center lg:justify-start mt-2 animate__animated animate__headShake animate__delay-3s">
          <div
            class="bg-tijolo hover:bg-tijolopink text-2xl text-neutral-700 font-bold hover:text-white py-4 px-8 border border-tijolopink hover:border-transparent rounded lg:w-auto w-full">
            <a href="/reservas" class="w-full flex">
              Reservas
            </a>
          </div>
          {{-- @include('partials/snippets/reservation') --}}
        </div>
      </div>
      <picture class="right mt-8 lg:mt-0 animate__animated animate__fadeInUp animate__slow lg:w-1/2">
        <source media="(max-width: 600px)" srcset="@asset('images/TijoloEntrada.webp')" width="570" height="545">
        <source media="(min-width: 601px)" srcset="@asset('images/TijoloEntrada.webp')" width="604" height="650">
        <img src="@asset('images/TijoloEntrada.webp')" alt="Tijolo CWB restaurante foto interior" width="604"
          height="650">
      </picture>
    </div>

  </div>


</section>

{{-- <div class=" flex justify-center items-center bg-green-100 ">
  <div class="flex flex-row justify-center social-icons my-10 gap-24 fill-white">
    @include ('partials.socialicons')
  </div>
</div> --}}