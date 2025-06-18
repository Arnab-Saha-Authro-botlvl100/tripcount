{{-- <img src={{url('/image/logo.png')}} alt="logo" width="200px" height="100px"/> --}}
{{-- <img src={{url('/image/tripcount.png')}} alt="logo" width="200px" height="100px"/> --}}

<style>
  @media (max-width: 768px) {
    .responsive-logo {
      width: 150px !important;
    }
  }
  @media (max-width: 480px) {
    .responsive-logo {
      width: 120px !important;
    }
  }
</style>

<img 
  src="{{url('/image/tripcount.png')}}" 
  alt="logo" 
  class="responsive-logo" 
  style="
    max-width: 100%;
    height: auto;
    width: 200px;
  " 
/>