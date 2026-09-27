<p>Hello <strong>{{ $details['name'] }}</strong>,</p>
<p>Thank you for reaching out to us. 
We are working on your issue (<strong>#{{ $details['ticket_no'] }}</strong>) and will get back to you soon. 
Please let us know if you have any more questions. We will be happy to help.
</p>
Thanks,
<br>
{{ config('maildoll.site_name') }}