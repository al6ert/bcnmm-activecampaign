

<form class="col-md-10 col-lg-5 col-xl-4" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="bcnmm-activecampaign-simple-form">
  
  <input type="hidden" name="action" value="simple_form_submitted">
  
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">    
  </div>

  <div class="form-group">    
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="Enter email">
    <small id="email-help" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>  
  
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="checkbox">
    <label class="form-check-label" for="checkbox">Check me out</label>
  </div>  
  
  <button type="submit" class="btn btn-primary">Submit</button>
  
</form>