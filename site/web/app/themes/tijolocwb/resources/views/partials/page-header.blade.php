<div class="page-header not-prose mt-12 mb-4 md:mt-24 text-center text-6xl lg:text-8xl">
  <h1 class="text-center mt-8 font-bold animate__animated animate__fadeInDown">{!! $title !!}</h1>
</div>

@php
// Somente para a categoria Dia a Dia
  $descricao_categoria = category_description();
  if (!empty($descricao_categoria)) {
      echo '<div id="categoria-descricao" class="text-center">' . $descricao_categoria . '</div>';
  }
@endphp 