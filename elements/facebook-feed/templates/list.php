<?php
/**
 * Template: Facebook List
 */
?>
<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">
    {{=it.attachment}}
    <div class='eael-content'>
        <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">
            <img class="media-object" src="{{=it.author_picture}}">
        </a>
        <div class="media-body">
            <p>
                <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>
                <span class="author-title">{{=it.author_name}}</span>
                <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>
            </p>
            <div class='text-wrapper'>
                <p class="social-feed-text">{{=it.text}} </p>
                <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More</a></p>
            </div>
        </div>
    </div>
</div>