<footer class="content-info bg-tijolo text-neutral-700 py-4">
  <div class="container">
    <div class="up-footer flex flex-col lg:flex-row justify-center items-center">
      <div class="left-footer">
        <div class="logo-icons flex flex-col items-center justify-between gap-y-10">
          <a class="brand" href="{{ home_url('/') }}">
            <div class="text-neutral-700 logohome relative hover:scale-105 transition duration-300 ease-in-out">
              <div class="text-8xl">Tijolo</div>
              <div class="text-lg absolute top-[85%] right-0">comidas e vinhos</div>
            </div>
          </a>
          <div id="social-icons" class="flex flex-row justify-between fill-neutral-700 gap-x-12">
            @include ('partials.socialicons')
          </div>
        </div>

        {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'flex footer-nav gap-4 w-full
        justify-center items-center lg:justify-start
        flex-row my-8 nav text-xl md:text-xl relative', 'echo' => false]) !!}
      </div>

      {{-- <div id="social-icons" class="right-footer flex flex-row justify-between fill-neutral-700 gap-x-24">
        @include ('partials.socialicons')
      </div> --}}
    </div>

    <div
      class="down-footer flex flex-col text-center text-base gap-y-6 lg:flex-row justify-center w-full border-t border-solid border-neutral-500 pt-6">

      <div class="left-footer">
        <section id="endereco" class="text-center">          
          <p><span>📍</span> R. São Francisco, 179, lojas 2 e 3 – dentro da <a class="hover:underline" href="https://sfco179.com.br/"
              target="_blank">SFCO 179</a> <br> Centro Histórico, Curitiba - PR</p>
          <p class="text-base">
            <a title="Clique para a página dos horários" href="/horarios">> Horários</a>
          </p>
          {{-- <p>
            Estacionamento em frente (ter- sáb)
          </p>
          <p>
            Estacionamento conveniado R. Treze de maio 529 (ter- dom)
          </p> --}}
        </section>
      </div>
    </div>


    @php(dynamic_sidebar('sidebar-footer'))

  </div>

</footer>