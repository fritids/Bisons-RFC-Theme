<?php
wp_enqueue_script('formscripts');
?>

<header>
<h2>Payment Details</h2>
</header>

<form>
    <fieldset>
        <legend>Membership Fees</legend>
        <p>You have not yet indicated how you will be paying your membership fees. Select a payment method below and click the 'next' button.</p>
        <div>
            <label class="smalllabel" for="paymethod">Payment Method</label>
            <select  name="paymethod" id="paymethod">
            </select>
        </div>
        <button type="submit">Next...</button>
    </fieldset>
</form>