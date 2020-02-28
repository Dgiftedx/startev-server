            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="hidden_status" value="{{$value}}" @if($value) checked @endif>
                <label class="custom-control-label" for="hidden_status">Toggle this to
                    @if($value == 1)
                        manually publish feeds.
                    @else
                        auto publish feeds.
                    @endif
                </label>
            </div>