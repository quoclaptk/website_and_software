{{ content() }}

<div class="login-or-signup">
    <div class="row">

        <div class="span6">
            <div class="page-header">
                <h2>Log In</h2>
            </div>
            {%include 'session/login.volt'%}
        </div>

        <div class="span6">
            <div class="page-header">
                <h2>Don't have an account yet?</h2>
            </div>

            <p>Create an account offers the following advantages:</p>
            <ul>
                <li>Create, track and export your invoices online</li>
                <li>Gain critical insights into how your business is doing</li>
                <li>Stay informed about promotions and special packages</li>
            </ul>

            <div class="clearfix center">
                {{ link_to('session/signup', 'Sign Up', 'class': 'btn btn-primary btn-large btn-success') }}
            </div>
        </div>

    </div>
</div>