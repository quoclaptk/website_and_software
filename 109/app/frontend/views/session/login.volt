
	{{ form('session/login') }}
		<fieldset>
                    <div class="control-group">
                        <label class="control-label" for="email">Username/Email</label>
                        <div class="controls">
                            		{{ form.render('email') }}
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="password">Password</label>
                        <div class="controls">
								{{ form.render('password') }}
								{{ link_to("session/forgotPassword", "Forgot password") }}
                        </div>
                    </div>

					<div class="remember">
						
					</div>
                    <div class="checkbox">
                    	{{ form.render('remember') }}
						{{ form.label('remember') }}
					</div>
                    
					{{ form.render('csrf', ['value': security.getToken()]) }}
                    <div class="form-actions">
							{{ form.render('go') }}
                    </div>
                </fieldset>

	</form>