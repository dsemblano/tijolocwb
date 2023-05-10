@include('partials/snippets.appheightjs')

<section id="tela1" class="home-section bg-neutral-700 py-12 bg-no-repeat bg-bottom lg:bg-right-top">
    {{-- <h1 class="flex justify-center">
      <img id="tijolo_logo" width="376" height="262" src="@asset('images/logotijolo.png')" alt="Tijolo CWB"/>
    </h1> --}}
    <div id="logo" class="container mx-auto">
      <div class="flex flex-col lg:flex-row gap-x-7 text-tijolo">
        <div class="left flex flex-col w-full lg:w-1/2 justify-center text-center lg:text-left lg:justify-start gap-y-8">
          <div class="logohome text-7xl lg:text-9xl">
            <p class="">A gente cozinha</p>
            <p> pra todo mundo</p>
            {{-- <span class="text-2xl lg:text-5xl absolute top-[90%] lg:top-[95%] right-0">comidas e vinhos</span> --}}
          </div>
          <p class="text-2xl">
            A Tijolo CWB é um projeto de comida descomplicada. Com exceção dos pães e chocolate, nossos preparos são todos feitos
            pela nossa equipe de cozinha: os leites vegetais, os molhos dos sanduíches, as bases de todos os pratos. Servimos
            bocaditos, sanduíches, pastas e molhos especiais, produzidos com insumos não refinados e minimamente processados.
            Nosso conceito é baseado no slow food, na experiência, na produção com cuidado e atenção plena, sem a pressa do fast
            food e do cotidiano. Trabalhamos com a ideia de comida saudável e muito saborosa, oferecemos comida de verdade. Grande
            parte do nosso cardápio é vegano, e nossa confeitaria preza por doces plant based.
          </p>
          <p class="text-2xl">
            Faça sua reserva abaixo!
          </p>
          <div class="flex justify-center lg:justify-start mt-8">
            <button class="flex justify-center bg-tijolo hover:bg-tijolopink text-xl text-neutral-700 font-semibold hover:text-white py-4 px-8 border border-tijolopink hover:border-transparent rounded">
              <a href="https://www.getinapp.com.br/curitiba/tijolo-cwb" target="_blank" class="">
                Reservas
              </a>
            </button>
          </div>
        </div>
        <div class="right mt-12 lg:mt-0">
          <img class="w-full" id="tijolo_logo"  src="@asset('images/pratos/pratohome1.jpg')" alt="Tijolo CWB"/>
        </div>
      </div>
      
    </div>

    
</section>

{{-- <div class=" flex justify-center items-center bg-green-100 ">
  <div class="flex flex-row justify-center social-icons my-10 gap-24 fill-white">
    @include ('partials.socialicons')
  </div>
</div> --}}
