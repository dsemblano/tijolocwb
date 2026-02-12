<div class="page-header not-prose mt-14 mb-12 text-tijolovermelho text-center text-6xl">
  <h1 class="text-center font-bold animate__animated animate__fadeInDown">{!! $title !!}</h1>
</div>

@php
// Somente para a categoria Dia a Dia
  $descricao_categoria = category_description();
  if (!empty($descricao_categoria)) {
      echo '<div id="categoria-descricao" class="text-center">' . $descricao_categoria . '</div>';
  }
@endphp 