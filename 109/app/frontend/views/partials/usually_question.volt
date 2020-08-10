{% set usuallyQuestion = usually_question_service.getListQuestionAnswers() %}
{% if usuallyQuestion != '' %}
<div class="container-fluid" id="register-form" style="background-image:url({{ usuallyQuestion['photo'] }});">
    <div class="row">
        <div class="box-bg ">
            <div class="form-content">
                <div id="Tquest">
                    <div class="row">
                        <div class="box-content">
                            <div class="col-md-6 col-md-offset-6">
                                <div class="box-header">
                                    <h3 class="animated bounceInDown go">{{ usuallyQuestion['name'] }}</h3>
                                    <p class="text-center animated growIn slow go">{{ usuallyQuestion['slogan'] }}</p>
                                </div>
                                <div class="panel-group" id="accordion">
                                	{% for key,answer in usuallyQuestion['answers'] %}
                                    <div class="panel panel-default animated slow go{% if key == 0 %} in{% endif %}">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" class="accordion-toggle{% if key != 0 %} collapsed{% endif %}" data-parent="#accordion" href="#{{ key }}collapseOne"> {{ answer.question }}</a>
                                            </h4>
                                        </div>
                                        <div id="{{ key }}collapseOne" class="panel-collapse collapse animated slow go{% if key == 0 %} in{% endif %}">
                                            <div class="panel-body">
                                                <div class="answer-content">{{ answer.answer }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endif %}