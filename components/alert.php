<?php
  if (isset($successMsg)) {
    foreach ($successMsg as $msg) {
      echo '<script>swal("'.$msg.'", "", "success");</script>';
    }
  }

  if (isset($warningMsg)) {
    foreach ($warningMsg as $msg) {
      echo '<script>swal("'.$msg.'", "", "warning");</script>';
    }
  }

  if (isset($infoMsg)) {
    foreach ($infoMsg as $msg) {
      echo '<script>swal("'.$msg.'", "", "info");</script>';
    }
  }

  if (isset($errorMsg)) {
    foreach ($errorMsg as $msg) {
      echo '<script>swal("'.$msg.'", "", "error");</script>';
    }
  }
?>