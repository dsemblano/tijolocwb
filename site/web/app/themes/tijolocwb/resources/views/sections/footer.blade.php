<footer class="content-info bg-tijolo text-neutral-700 py-4 mt-4">
  <div class="container">
    <div class="up-footer flex flex-col lg:flex-row justify-between items-center mb-4">
      <div class="left-footer">
        <div class="flex mb-6">
          <div class="logo-icons flex flex-col gap-y-10">
            <a class="brand" href="{{ home_url('/') }}">
              <div id="logo" class="text-neutral-700 logohome relative hover:scale-105 transition duration-300 ease-in-out">
                <div class="text-8xl">Tijolo</div>
                <div class="text-lg absolute top-[85%] right-0">comidas e vinhos</div>
              </div>
            </a>
            <div id="social-icons" class="flex flex-row justify-between fill-neutral-700 gap-x-12">
              @include ('partials.socialicons')
            </div>
          </div>
          
        </div>
        {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'flex flex-col footer-nav gap-4 w-full
        justify-center items-center lg:justify-start
        lg:flex-row my-8 nav text-xl md:text-xl relative', 'echo' => false]) !!}
      </div>

      {{-- <div id="social-icons" class="right-footer flex flex-row justify-between fill-neutral-700 gap-x-24">
        @include ('partials.socialicons')
      </div> --}}
    </div>

    <div
      class="down-footer flex flex-col gap-y-6 text-center lg:flex-row justify-between w-full border-t border-solid border-neutral-500 pt-6">

      <div class="left-footer">
        <section id="endereco" class="text-lg">
          <p>R. São Francisco, 179, lojas 2 e 3 – centro histórico,
            Curitiba-PR</p>
            <p>
            Estacionamento em frente (ter- sáb)
          </p>
          <p>
            Estacionamento conveniado R. Treze de maio 529 (ter- dom)
          </p>
        </section>
      </div>

      <div class="right-footer fill-neutral-700">
        <section id="horario" class="text-lg">
          <p>Horários:</p>
          <p>
            Terça a Sexta: <time>11:15</time> a <time>22:00</time>
          </p>
          <p>
            Terça a Sexta (almoço) | <time>11:15</time> a <time>15:00</time>
          </p>
          <p>
            Sábado e feriado: <time>12:00</time> a <time>23:00</time>
            Domingo: <time>12:00</time> a <time>17:00</time>
          </p>
        </section>
      </div>
    </div>


    @php(dynamic_sidebar('sidebar-footer'))

  </div>

</footer>