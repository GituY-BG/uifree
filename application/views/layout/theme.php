<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$theme = $this->session->userdata('theme') ? $this->session->userdata('theme') : 'light';
?>
<link href="<?php echo base_url('assets/css/theme.css'); ?>" rel="stylesheet">
<script>
    document.documentElement.setAttribute('data-theme', '<?php echo htmlspecialchars($theme); ?>');
</script>