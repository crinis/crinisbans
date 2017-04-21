/* eslint-disable no-undef */
import './libs/jquery.timeDurationPicker.js'
jQuery(function () {
  var durationPicker = jQuery('#cb-duration-picker')
  if (durationPicker.length !== 0) {
    var duration = jQuery('#cb-duration')
    durationPicker.timeDurationPicker({
      defaultValue: function () {
        return duration.val()
      },
      onSelect: function (element, seconds, humanDuration) {
        durationPicker.val(humanDuration)
        duration.val(seconds)
      }
    })
  }
})
