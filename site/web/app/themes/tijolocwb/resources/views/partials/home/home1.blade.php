@include('partials/snippets.appheightjs')

<section id="tela1" class="home-section bg-neutral-700 py-6 bg-no-repeat bg-bottom lg:bg-right-top">
    <div class="container mx-auto">
      <div class="flex flex-col lg:flex-row gap-x-10 text-tijolo">
        <div class="left flex flex-col w-full lg:w-1/2 justify-center lg:justify-start">
          {{-- <div class="logohome text-5xl text-center xl:text-7xl mb-6">
            <h2 class="text-5xl">A gente cozinha pra todo mundo</h2>
            <span class="text-2xl lg:text-5xl absolute top-[90%] lg:top-[95%] right-0">comidas e vinhos</span>
          </div> --}}

          <article class="text-center lg:text-left xl:mt-2">
            <p class="mb-6">
              A Tijolo CWB é um projeto de comida descomplicada. Com exceção dos pães e chocolate, nossos preparos são todos feitos
              pela nossa equipe de cozinha: os leites vegetais, os molhos dos sanduíches, as bases de todos os pratos.
            </p>
            <p class="mb-6">
              Servimos bocaditos, sanduíches, pastas e molhos especiais, produzidos com insumos não refinados e minimamente
              processados.</p>
            <p class="mb-6">
              Nosso conceito é baseado no slow food, na experiência, na produção com cuidado e atenção plena, sem a pressa do fast
              food e do cotidiano. Trabalhamos com a ideia de comida saudável e muito saborosa, oferecemos comida de verdade. Grande
              parte do nosso cardápio é vegano, e nossa confeitaria preza por doces plant based.</p>
            <p class="mb-6">
              Faça sua reserva abaixo!
            </p>
          </article>
          
          <div class="flex justify-center lg:justify-start mt-2">
            <button class="flex justify-center bg-tijolo hover:bg-tijolopink text-xl text-neutral-700 font-semibold hover:text-white py-4 px-8 border border-tijolopink hover:border-transparent rounded">
              <a href="https://www.getinapp.com.br/curitiba/tijolo-cwb" target="_blank" class="">
                Reservas
              </a>
            </button>
          </div>
        </div>
        <div class="right mt-8 lg:mt-0">
          <img src="@asset('images/home-tijolo.webp')" alt="Tijolo CWB"/>
        </div>
      </div>
      
    </div>

    
</section>

{{-- <div class=" flex justify-center items-center bg-green-100 ">
  <div class="flex flex-row justify-center social-icons my-10 gap-24 fill-white">
    @include ('partials.socialicons')
  </div>
</div> --}}
